<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\ObjectUtil;
use Throwable;

class BaseRestController extends Controller {

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
}
