<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;


use App\Http\Controllers\Common\Controller;

class TenantAdminController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $text = "Hello World! Estamos em Tenant Admin.";
        return $text;
    }
}
