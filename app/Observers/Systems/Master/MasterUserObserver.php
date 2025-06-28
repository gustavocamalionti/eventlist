<?php

namespace App\Observers\Systems\Master;

use App\Libs\ViewsModules;
use App\Observers\BaseObserver;

class MasterUserObserver extends BaseObserver
{
    public function title()
    {
        return ViewsModules::PANEL_USERS;
    }
}
