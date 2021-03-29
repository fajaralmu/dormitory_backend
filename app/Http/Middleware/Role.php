<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {
        $user = $request->user();

        if (! $user->hasRole($role)) {
            return redirect('/home')->with('status', 'Not allowed to access that page');
        }

        return $next($request);
    }
}
