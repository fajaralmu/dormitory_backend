<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth; 

class AuthService
{
   
    // public function register(WebRequest $request) : WebResponse
    // {
    //     $requestModel = $request->userMuseodel;
    //     if (is_null($requestModel)) {
    //         throw new Exception("User data not found");
    //     }
    //     $savedUser = $this->saveUser($requestModel);
    //     $response = new WebResponse();
    //     $response->user = $savedUser;
    //     return $response;
    // }

    public function logout() : WebResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        $response = new WebResponse();
        return $response;
    }
    /**
     * 
     * @return string token
     */
    public function login(Request $request) : string
    {
       
        $email = $request->post('email');
        $cred = ['email' => $email, 'password' => $request->post('password')];
       
        $userCount = User::where(['email' => $email])->count();
        if ($userCount < 1) {
            throw new Exception("Email : $email not found");
        }
        
        $token = JWTAuth::customClaims([
            'key' => "VALUE"
            // , 'jti' => $email.Str::random(50)
            ])->attempt($cred);
        // $token = auth()->attempt($cred);
        if ($token) {
            return $token;
        } else {
            throw new Exception("Login Failed: password invalid");
        }
    }

    public function resetUserPassword(WebRequest $request) : WebResponse
    {
        $response = new WebResponse();
        $userEmail = $request->userModel->email;
        $user = User::where('email', $userEmail)->first();
        if (is_null($user)) {
            throw new Exception("User does not exist");
        }
        $user->password = Hash::make(config('common.default_password'));
        $user->save();
        return $response;
    }
}