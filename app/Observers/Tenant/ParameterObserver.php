<?php

namespace App\Observers\Tenant;

use App\Libs\ViewsModules;
use App\Observers\BaseObserver;

class ParameterObserver extends BaseObserver
{
    public function title()
    {
        return ViewsModules::PANEL_PARAMETERS;
    }
}
