<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Common\Controller;
use App\Models\Systems\Master\MasterParameter;

class TenantAdminController extends Controller
{
    public function __construct() {}

    public function index()
    {
        try {
            $pageTitle = "Dashboard";
            $user = Auth::user();
            $buysReceived = ["qtd" => 0, "value" => "0", "net_value" => "0"];
            $buysConfirmed = ["qtd" => 0, "value" => "0", "net_value" => "0"];
            $buysAwaiting = ["qtd" => 0, "value" => "0", "net_value" => "0"];
            $vouchers = [];
            $qtdStores = 0;
            $parameters = MasterParameter::find(1);
            return view(
                "legacy.systems.tenant.modules.admin.pages.dashboard.dashboard",
                compact(
                    "pageTitle",
                    "parameters",
                    "user",
                    "buysReceived",
                    "buysConfirmed",
                    "buysAwaiting",
                    "vouchers",
                    "qtdStores"
                )
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_DASHBOARD,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_DASHBOARD,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
