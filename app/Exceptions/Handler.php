<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = ["current_password", "password", "password_confirmation"];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson() || $request->header('X-Inertia')) {
            $status = 500;
            $page = 'Error/500';

            if ($exception instanceof HttpExceptionInterface) {
                $status = $exception->getStatusCode();
            }

            switch ($status) {
                case 403:
                    $page = 'Error/403';
                    break;
                case 404:
                    $page = 'Error/404';
                    break;
                case 503:
                    $page = 'Error/503';
                    break;
            }

            return Inertia::render($page)->toResponse($request)->setStatusCode($status);
        }

        return parent::render($request, $exception);
    }
}
