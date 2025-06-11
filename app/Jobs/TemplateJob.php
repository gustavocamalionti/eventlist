<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Exceptions\NotEntityDefined;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TemplateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [300, 600];
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Executa a lógica específica do job
        if (is_callable([$this, "object"])) {
            $this->object($this->data);
        } else {
            throw new NotEntityDefined();
        }
    }
}
