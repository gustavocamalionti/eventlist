<?php

namespace App\Http\Controllers\Systems\Master\Modules\Admin;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Common\Controller;

class MasterAdminController extends Controller
{
    public function __construct() {}

    public function index()
    {
        if (tenancy()->tenant == null) {
            return Inertia::render("systems/master/modules/admin/pages/Dashboard");
        };
    }
}
