<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyWebhookAccessToken
{
    public function handle(Request $request, Closure $next)
    {
        // Recupera o token do header
        $accessTokenHeader = $request->header("asaas-access-token");

        // Recupera o token configurado no .env
        $expectedToken = env("WEBHOOK_ACCESS_TOKEN");

        // Verifica se o token da requisição é igual ao token do .env
        if ($accessTokenHeader !== $expectedToken) {
            return response()->json(["error" => "Access Denied"], 403);
        }

        return $next($request);
    }
}
