<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService
{
    public function updateProfile(Request $request, User $userModel) : WebResponse
    {
        $response = new WebResponse();
        $user = $request->user();
        $user->email = $userModel->email;
        $user->display_name = $userModel->display_name;
        $user->name = $userModel->name;
        
        if (is_null($userModel->oassword)
            && "" != $userModel->password) {
            $user->password = Hash::make(($userModel->password));
        }
        $user->email = $userModel->email;
        $user->save();
        return $response;
    }
    public function register(WebRequest $request) : WebResponse
    {
        $requestModel = $request->userMuseodel;
        if (is_null($requestModel)) {
            throw new Exception("User data not found");
        }
        $savedUser = $this->saveUser($requestModel);
        $response = new WebResponse();
        $response->user = $savedUser;
        return $response;
    }

    public function logout(User $requestUser) : WebResponse
    {
        $user = User::find($requestUser->id);
        $user->api_token = null;
        $user->save();
        $response = new WebResponse();
        return $response;
    }

    public function saveUser(User $requestModel, bool $new = true) : User
    {
        $user = new User();
        $oldDataExist = false;
        if (!$new) {
            $user = User::find($requestModel->id);
            if (is_null($user)) {
                throw new Exception("Existing data not found");
            }
            $oldDataExist = true;
        }
        $user->email = $requestModel->email;
        $user->name = ($requestModel->name);
        $user->display_name = ($requestModel->display_name);

        //if will update user and user.password field is not provided
        if ((is_null($requestModel->password) || "" == $requestModel->password) && $oldDataExist) {
            // $user->password = Hash::make(config('common.default_password')); leave as old record password
        } else {
            $user->password = Hash::make(($requestModel->password));
        }
        //if will update user and user.password field is not provided
        if ((!isset($requestModel->departement_id) && is_null($requestModel->departement_id) || "" == $requestModel->departement_id) && $oldDataExist) {
            // $user->password = Hash::make(config('common.default_password')); leave as old record password
        } else {
            $user->departement_id = ($requestModel->departement_id);
        }
        if (!$new) {
            # code... not updating ROLE
        } else {
            $user->role = ('user');
        }
        
        $user->save();
        return $user;
    }

    public function loginAttemp(Request $request) : string
    {
       
        $email = $request->post('email');
        $cred = ['email' => $email, 'password' => $request->post('password')];
       
        $dbUser = User::where(['email' => $email])->first();
        if (is_null($dbUser)) {
            throw new Exception("Email : $email not found");
        }
        if (Auth::attempt($cred)) {
            
            $token = Str::random(60);
            $hashedToken = hash('sha256', $token);
            $user = $this->updateApiToken($email, $hashedToken);
            return $hashedToken;
        } else {
            throw new Exception("Login Failed: password invalid");
        }
    }

    private function updateApiToken(string $email, string $token)
    {
        $user = User::where('email', $email)->first();
        $user->api_token = $token;
        $user->save();
        return $user;
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