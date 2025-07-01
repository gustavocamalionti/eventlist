<?php

namespace App\Providers;

use App\Models\Common\LogEmail;
use App\Models\Common\CustomColor;
use App\Validator\CustomValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Notifications\ResetPassword;

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
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            $isTenant = tenancy()->initialized;
            $routePrefix = $isTenant ? "tenant.auth" : "master.auth";
            return URL::route($routePrefix . "." . "password.reset", [
                "token" => $token,
                "email" => $notifiable->getEmailForPasswordReset(),
            ]);
        });
        // \URL::forceScheme("https");
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });

        Queue::after(function (JobProcessed $event) {
            $job = unserialize($event->job->payload()["data"]["command"]);

            if ($job->tenantId != null) {
                tenancy()->initialize($job->tenantId);
            }

            $getEvent = LogEmail::where("uuid", $event->job->payload()["uuid"])->first();
            if (!$event->job->hasFailed()) {
                $getEvent->update(["status" => "Success", "details" => "O e-mail foi enviado."]);
            }
        });

        Queue::failing(function (JobFailed $event) {
            $job = unserialize($event->job->payload()["data"]["command"]);

            if ($job->tenantId != null) {
                tenancy()->initialize($job->tenantId);
            }
            $exception = $event->exception->getMessage();
            $getEvent = LogEmail::where("uuid", $event->job->payload()["uuid"])->first();
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
