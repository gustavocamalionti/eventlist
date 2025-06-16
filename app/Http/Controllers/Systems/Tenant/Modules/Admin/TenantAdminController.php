<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Common\Controller;

class TenantAdminController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $text = "Hello World! Estamos em Tenant Admin.";
        return Inertia::render("systems/tenant/modules/admin/pages/Welcome", [
            "text" => $text,
        ]);
    }
}
