<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\User;
use App\Models\LogEmail;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumLinkFixed;
use App\Models\Tenant\CustomColor;
use App\Validator\CustomValidator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Queue;
use App\Services\Crud\CrudLinkService;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env(key: "APP_ENV") === "local" && request()->server(key: "HTTP_X_FORWARDED_PROTO") === "https") {
            URL::forceScheme(scheme: "https");
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // \URL::forceScheme("https");
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });

        Queue::after(function (JobProcessed $event) {
            $getEvent = LogEmail::where("uuid", $event->job->payload()["uuid"]);
            if (!$event->job->hasFailed()) {
                $getEvent->update(["status" => "Success", "details" => "O e-mail foi enviado."]);
            }
        });

        Queue::failing(function (JobFailed $event) {
            $exception = $event->exception->getMessage();
            $getEvent = LogEmail::where("uuid", $event->job->payload()["uuid"]);
            $getEvent->update(["status" => "Error", "details" => $exception]);
        });

        view()->composer("*", function ($view) {
            $colors = [];
            $contents = [];
            $style = "";

            // Rever lógica, entender se estamos em um subdominio tenant ou não
            // Importar CustomColor correto
            $colors = CustomColor::where("type", "style")->get();

            foreach ($colors as $reg) {
                $style = $style . "--" . str_replace("_", "-", $reg->key) . ":" . $reg->value . ";";
            }

            $content = [];

            $view->with([
                "customizations" => [
                    "styles" =>
                    "
                    <style>
                        :root{
                            " .
                        $style .
                        "
                        }
                    </style>
                    ",
                    "contents" => $content,
                ],
            ]);
        });
    }
}
