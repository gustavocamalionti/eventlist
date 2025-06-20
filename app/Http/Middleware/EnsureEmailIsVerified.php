<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail())
        ) {
            $routePrefix = $this->getRoutePrefix($request);

            $finalRedirect = $redirectToRoute ?? $routePrefix . "verification.notice";

            return $request->expectsJson()
                ? abort(403, "Your email address is not verified.")
                : Redirect::guest(URL::route($finalRedirect));
        }

        return $next($request);
    }

    protected function getRoutePrefix($request): string
    {
        // Verifica se a rota atual começa com um nome específico
        $routeName = optional($request->route())->getName();

        if (str_starts_with($routeName, "tenant.")) {
            return "tenant.auth.";
        }

        if (str_starts_with($routeName, "master.")) {
            return "master.auth.";
        }

        return ""; // fallback, se não houver prefixo
    }
}
