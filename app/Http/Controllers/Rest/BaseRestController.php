<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\ObjectUtil;
use Throwable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Claims\Audience;
use Tymon\JWTAuth\Claims\Expiration;
use Tymon\JWTAuth\Claims\IssuedAt;
use Tymon\JWTAuth\Claims\Issuer;
use Tymon\JWTAuth\Claims\JwtId;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;
use Tymon\JWTAuth\Support\Utils;
use Tymon\JWTAuth\Validators\PayloadValidator;
use Tymon\JWTAuth\Claims\Collection as ClaimCollection;
use Tymon\JWTAuth\Claims\NotBefore;
use Tymon\JWTAuth\Claims\Subject;

class BaseRestController extends Controller
{

    public function __construct()
    {
        // $this->middleware('api');
    }
 
    /**
     * construct request payload object and inject auth user to payload
     */
    protected function getWebRequest(Request $request) : WebRequest
    {
        $result =  ObjectUtil::arraytoobj(new WebRequest(), $request->json());

        if (!is_null($request->user())) {
            $result->user = $request->user();
        }
        
        return $result;
    }

    protected function jsonResponseAndResendToken(WebResponse $response) : JsonResponse
    {
        return $this->jsonResponse($response, $this->resentToken());
    }

    protected function webResponse($code = null, $message = null)
    {
        $response = new WebResponse();

        if (null != $code) {
            $response->code = $code;
        }
        if (null!=$message) {
            $response->message = $message;
        }
        $statusCode = 200;
        if ($code!="00") {
            $statusCode = 500;
        }
        return response(json_encode(($response)), $statusCode);
    }
 
    
    protected function jsonResponse(WebResponse $response, array $header = null)
    {
        if (null == $header) {
            return response()->json(($response));
        }
        
        return response()->json(($response), 200, $header);
    }

    protected function errorResponse(Throwable $th)
    {
        // throw $th;
        $response = new WebResponse();
        $response->message = $th->getMessage();
        $response->code = "-1";
        return response()->json(($response), 500);
    }

    protected function headerApiToken(string $api_token)
    {
        return ['api_token'=>$api_token, 'Access-Control-Expose-Headers'=>'api_token'];
    }
    protected function resentToken()
    {
        try {
           
            $new_token = JWTAuth::encode($this->newJwtPayload());
            return ['api_token'=>$new_token, 'Access-Control-Expose-Headers'=>'api_token'];
        } catch (Throwable $th) {
            out("ERROR Refreshing TOKEN: ". $th->getMessage());
            return [];
        }
        
    }

    private function newJwtPayload() : Payload
    {
        $token = JWTAuth::getToken();
        $payload_old  = JWTAuth::getPayload($token);
        $classMap = [
            'aud' => new Audience($payload_old->get('aud')),// Audience::class,
            //TODO: get TTL from env('JWT_TTL', 60)
            'exp' => new Expiration(Utils::now()->addMinutes(60)->getTimestamp()),
            'iat' => new IssuedAt($payload_old->get('iat')),// IssuedAt::class,
            'iss' => new Issuer($payload_old->get('iss')),//Issuer::class,
            'jti' => new JwtId($payload_old->get('jti')),// JwtId::class,
            'nbf' => new NotBefore($payload_old->get('nbf')),// NotBefore::class,
            'sub' => new Subject($payload_old->get('sub'))//Subject::class,
        ];
        $newClaims = new ClaimCollection($classMap);
         
        return new Payload($newClaims, new PayloadValidator());
    }
}
