<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = env('AUTHORIZATION_KEY');

        if(!$request->hasHeader('Authorization') || !Hash::check($request->header('Authorization'), $key)) {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
