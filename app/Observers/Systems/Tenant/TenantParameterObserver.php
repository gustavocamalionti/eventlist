<?php

namespace App\Observers\Systems\Tenant;

use App\Libs\ViewsModules;
use App\Observers\BaseObserver;

class TenantParameterObserver extends BaseObserver
{
    public function title()
    {
        return ViewsModules::PANEL_PARAMETERS;
    }
}
