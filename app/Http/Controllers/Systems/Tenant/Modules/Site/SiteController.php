<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Site;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Common\Controller;

class SiteController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $text = "OsCumpads";
        return Inertia::render("systems/tenant/modules/site/pages/Welcome", [
            "text" => $text,
        ]);
    }
}
