<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Disabled Checking becusue to check test caases 
        // return $next($request);

        // $user = $request->session()->get("auth");

        if (!Auth::guard('new_user')->check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
