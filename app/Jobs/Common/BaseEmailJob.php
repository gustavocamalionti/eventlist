<?php

namespace App\Jobs\Common;

use Illuminate\Bus\Queueable;
use App\Models\Common\LogEmail;
use App\Exceptions\NotEntityDefined;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BaseEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $backoff = [300, 600];
    protected $emailObject;
    protected $arrayEmailTo;
    protected $arrayEmailBcc;

    public $tenantId;
    /**
     * Create a new job instance.
     */
    public function __construct($arrayEmailTo, $dataForEmail, $arrayEmailBcc = null, $tenantId = null)
    {
        $this->arrayEmailTo = $arrayEmailTo;
        $this->emailObject = $this->resolveEmailObject($dataForEmail);
        $this->arrayEmailBcc = $arrayEmailBcc;
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->tenantId != null) {
            tenancy()->initialize($this->tenantId);
        }
        $event_exists = LogEmail::where("uuid", $this->job->payload()["uuid"]);
        if ($event_exists->count() == 0) {
            $logEvent = new LogEmail();
            $logEvent->uuid = $this->job->payload()["uuid"];
            $logEvent->users_id = isset($this->emailObject->data["users_id"])
                ? $this->emailObject->data["users_id"]
                : 0;
            $logEvent->job_title = $this->job->resolveName();
            $logEvent->status = "Processing";
            $logEvent->customers_id = isset($this->emailObject->data["customers_id"])
                ? $this->emailObject->data["customers_id"]
                : 0;
            $logEvent->buys_id = isset($this->emailObject->data["buys_id"]) ? $this->emailObject->data["buys_id"] : 0;
            $logEvent->email = $this->emailObject->data["email"];
            $logEvent->details = "O e-mail está sendo processado, aguarde.";
            $logEvent->save();
        }
        $mail = Mail::to($this->arrayEmailTo);
        if ($this->arrayEmailBcc != null) {
            $mail->bcc($this->arrayEmailBcc);
        }
        $mail->send($this->emailObject);
    }

    public function resolveEmailObject($dataForEmail)
    {
        if (!method_exists($this, "emailObject")) {
            throw new NotEntityDefined();
        }

        return $this->emailObject($dataForEmail);
    }
}
