<?php

namespace App\Observers\Systems\Tenant;

use App\Libs\ViewsModules;
use App\Observers\BaseObserver;

class TenantUserObserver extends BaseObserver
{
    public function title()
    {
        return ViewsModules::PANEL_USERS;
    }
}
