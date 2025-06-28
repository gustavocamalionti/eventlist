<?php

namespace App\Http\Controllers\Common;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function isTenant(): bool
    {
        return tenancy()->initialized;
    }
    public function isMaster(): bool
    {
        return tenancy()->initialized;
    }
}
