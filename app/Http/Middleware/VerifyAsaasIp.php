<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyAsaasIp
{
    /**
     * Lista de IPs permitidos (oficiais do Asaas).
     */
    private array $allowedIps = [
        "172.18.0.1",
        "172.20.0.1",
        "18.230.8.159",
        "52.67.12.206",
        "54.94.136.112",
        "54.94.183.101",
        "54.94.35.137",
        "54.232.219.32",
        "54.232.48.115",
        "54.207.175.46",
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (!in_array($ip, $this->allowedIps)) {
            Log::warning("Tentativa de acesso ao webhook do Asaas por IP não autorizado: {$ip}");
            return response()->json(["error" => "Unauthorized IP"], 403);
        }

        return $next($request);
    }
}
