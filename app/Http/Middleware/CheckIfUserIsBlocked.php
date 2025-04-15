<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CheckIfUserIsBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->blocked == 1) {
            Auth::logout();

            // Optional: redirect to login with error
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been blocked.',
            ]);
        }
        
        return $next($request);
    }
}
