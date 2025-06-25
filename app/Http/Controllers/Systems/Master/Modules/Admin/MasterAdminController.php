<?php

namespace App\Http\Controllers\Systems\Master\Modules\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Common\Controller;

class MasterAdminController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $text = "Hello World! Estamos em Master Admin.";
        return $text;
    }
}
