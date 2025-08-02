<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Site;

use Inertia\Inertia;
use App\Http\Controllers\Common\Controller;
use App\Services\Systems\Tenant\Crud\CrudParameterService;

class SiteController extends Controller
{
    protected $crudParameterService;
    public function __construct(CrudParameterService $crudParameterService)
    {
        $this->crudParameterService = $crudParameterService;
    }

    public function index()
    {
        $text = "OsCumpads";
        return Inertia::render("systems/tenant/modules/site/pages/Welcome", [
            "text" => $text,
        ]);
    }
}
