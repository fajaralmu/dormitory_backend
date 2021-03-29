<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
    	$user = $request->user();
    	$prefix = collect($user->roles)->intersect(['student', 'employee'])->first();
    	$aliases = ['employee' => 'teacher', 'student' => 'student'];
    	return redirect()->route(array_get($aliases, $prefix) . '.dashboard');
    }
}
