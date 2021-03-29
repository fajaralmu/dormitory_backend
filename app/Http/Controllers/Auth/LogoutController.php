<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LogoutController extends Controller
{
    use AuthenticatesUsers;

    // Override
    public function logout(Request $request)
    {
    	$this->prepareOut($request);

        $this->guard()->logout();

        // $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    public function loggedOut(Request $request)
    {
        $oldUser = $request->session()->get('old_user');

        $request->session()->invalidate();

        $password = $oldUser->password;
        // $secret = strrev(base64_encode((substr($password, mt_rand(5, 15), 5))));

        $data = [
        	'id' => $oldUser->id,
        	'email' => $oldUser->email,
        	// 'secret' => $secret,
        	'redirect' => config('app.url'),
        ];

        $token = base64_encode(json_encode($data));

        $targetPassportUrl = 'https://portalsekolah.test'; // TODO: put this URL in env

        if (config('app.env') === 'production') {
            $targetPassportUrl = 'https://myschool.web.id';
        }

        return redirect($targetPassportUrl . '/remote-logout/' . $token);
    }

    // Custom function
    public function prepareOut(Request $request)
    {
    	$request->session()->put('old_user', $request->user());
    }
}
