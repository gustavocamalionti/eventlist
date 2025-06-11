<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticatePanel
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
        // if (auth()->user()->roles->id == 0) {
        //     return redirect()->route("login");
        // }
        return $next($request);
    }
}
