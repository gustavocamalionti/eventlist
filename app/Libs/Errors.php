<?php

namespace App\Libs;

use App\Models\Common\LogError;

use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Errors
{
    public static function GetMessageError($status, $title, $action, $payload, $message, $code)
    {
        if ($message == null) {
            $message = "Falha, erro inesperado!";
        }

        self::SaveErrorLog($status, $title, $action, $payload, "code: " . $code . " | " . $message);
        switch ($status) {
            case EnumErrorsType::SQL:
                return self::GetMessageErrorSQL($status, $code, $message);
                break;
            case 1:
            case EnumErrorsType::GENERIC:
                return self::GetMessageErrorGeneric($status, $code, $message);
                break;
        }
    }

    private static function GetMessageErrorSQL($status, $code, $message)
    {
        if ($code == 23000) {
            //Integrity constraint violation

            if (strpos($message, "Integrity constraint violation: 1062 Duplicate")) {
                $code = 1062;
                $message = "Não foi possível completar a operação. <br> Registro duplicado !";
            } elseif (strpos($message, "Integrity constraint violation: 1451 Cannot delete or update")) {
                $code = 1451;
                $message = "Não foi possível completar a operação. <br> Registro está sendo utilizado !";
            }
        }

        return response()->json(["status" => $status, "coderro" => $code, "msgerro" => $message], 500);
    }

    private static function GetMessageErrorGeneric($status, $code, $message)
    {
        return response()->json(["status" => $status, "coderro" => $code, "msgerro" => $message], 500);
    }

    private static function SaveErrorLog($status, $title, $action, $payload, $message)
    {
        $route = Route::getFacadeRoot()->current();
        $log = new LogError();
        $log->status = $status;
        $log->route = $route ? "/" . $route->uri() : "N/A";
        $log->title = $title;
        $log->action = $action;
        $log->method = $route ? $route->methods()[0] : "N/A";
        $log->payload = $payload;
        $log->message = $message;
        $log->ip = request()->ip();
        if (Auth::check()) {
            $log->users_id = Auth::user()->id;
            $log->users_name = Auth::user()->name;
            $log->users_email = Auth::user()->email;
        }
        $log->save();

        return $log;
    }
}
