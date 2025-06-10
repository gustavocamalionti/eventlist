<?php

namespace App\Services\Site\Rules;

use Exception;
use App\Jobs\UpdateBuysJob;
use Illuminate\Database\QueryException;

class RulesUpdateBuysService
{
    public function __construct() {}

    public function __invoke()
    {
        try {
            UpdateBuysJob::dispatch();
        } catch (QueryException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }
}
