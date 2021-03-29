<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Auth;

class JoinController extends Controller
{
    public function create()
    {
    	return Socialite::driver('portalsekolah')->redirect();
    }

    public function store()
    {
    	$data = Socialite::driver('portalsekolah')->user();
        $user = User::where('id', $data->id)->first();

        if (is_null($user)) {
            return redirect()->route('upgrade');
        }

    	Auth::login($user);
    	return redirect('/home');
    }
}
