<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SyncronizeController extends Controller
{
    public function store(Request $request)
    {
    	$school_id = $request->get('school_id');
	    $secret = $request->get('secret');

	    $m2m_sync_secret = config('school.m2m_sync_secret');

	    $cmd = 'school:sync ' . $school_id;

	    if ($request->get('is_update')) {
	        $cmd = 'school:sync ' . $school_id . ' --update';
	    }

	    if ($secret === $m2m_sync_secret) {
	        $exitCode = \Artisan::call($cmd);
	        return ['school' => $school_id, 'success' => true];
	    }

	    return abort(404);
    }
}
