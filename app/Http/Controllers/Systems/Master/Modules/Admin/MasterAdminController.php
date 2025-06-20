<?php

namespace App\Http\Controllers\Systems\Master\Modules\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Common\Controller;

class MasterAdminController extends Controller
{
    public function __construct() {}

    public function index()
    {
        dd("oi");
        if (tenancy()->tenant == null) {
            return Inertia::render("systems/master/modules/admin/pages/Dashboard");
        }
    }
}
