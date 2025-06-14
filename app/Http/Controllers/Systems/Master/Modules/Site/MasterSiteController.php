<?php

namespace App\Http\Controllers\Systems\Master\Modules\Site;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Common\Controller;

class MasterSiteController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $text = "Hello World! Estamos em Master Site.";
        return Inertia::render("systems/master/modules/site/pages/Welcome", [
            "text" => $text
        ]);
    }
}
