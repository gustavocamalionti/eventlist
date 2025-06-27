<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumDecision;
use App\Libs\Enums\EnumErrorsType;

use Illuminate\Database\QueryException;
use App\Services\Crud\CrudBannerService;

use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudParameterService;
use App\Http\Requests\Panel\SaveBannersRequest;
use App\Services\Panel\Rules\RulesFilesService;
use App\Http\Requests\Panel\UpdateBannersRequest;
use App\Http\Controllers\Controller as Controller;
use App\Services\Panel\Rules\RulesDateTimeService;
use App\Services\Panel\Rules\RulesMaintenanceService;
use App\Services\Common\Rules\RulesBannerIsActiveService;
use App\Services\Crud\CrudLinkService;
use App\Services\Crud\CrudStoreService;

/**
 * Controller responsible for managing the banners in the administration panel.
 */
class BannerController extends Controller
{
    protected $crudParameterService;
    protected $crudBannerService;
    protected $crudLogAuditService;
    protected $crudStoreService;
    protected $crudLinkService;
    protected $rulesAchievedService;
    protected $rulesFilesService;
    protected $rulesDateTimeService;
    protected $rulesBannerService;
    protected $rulesMaintenanceService;
    protected $rulesBannerIsActiveService;

    /**
     * Class constructor, initializes necessary services and sets up permission middlewares.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudBannerService $crudBannerService Service for handling banners.
     * @param CrudLogAuditService $crudLogAuditService Service for handling audit logs.
     * @param CrudStoreService $crudLogAuditService Service for handling stores.
     * @param CrudLinkService $crudLogAuditService Service for handling Links.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for maintenance rules.
     * @param RulesFilesService $rulesFilesService Service for file validation rules.
     * @param RulesDateTimeService $rulesDateTimeService Service for handling date and time operations.
     * @param RulesBannerIsActiveService $rulesBannerIsActiveService Service for handling banner activation rules.
     * @return void
     */

    public function __construct(
        CrudParameterService $crudParameterService,
        CrudBannerService $crudBannerService,
        CrudLogAuditService $crudLogAuditService,
        CrudStoreService $crudStoreService,
        CrudLinkService $crudLinkService,
        RulesMaintenanceService $rulesMaintenanceService,
        RulesFilesService $rulesFilesService,
        RulesDateTimeService $rulesDateTimeService,
        RulesBannerIsActiveService $rulesBannerIsActiveService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudBannerService = $crudBannerService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->crudStoreService = $crudStoreService;
        $this->crudLinkService = $crudLinkService;

        $this->rulesMaintenanceService = $rulesMaintenanceService;
        $this->rulesFilesService = $rulesFilesService;
        $this->rulesDateTimeService = $rulesDateTimeService;
        $this->rulesBannerIsActiveService = $rulesBannerIsActiveService;

        $this->middleware("can:read_banners")->only(["bannersList", "bannersFilters"]);
        $this->middleware("can:read_banners_audit")->only(["getBannersHistory"]);
        $this->middleware("can:create_banners")->only(["bannersMaintenance", "bannersStore"]);
        $this->middleware("can:update_banners")->only([
            "bannersMaintenance",
            "bannersUpdate",
            "bannersStoreOrder",
            "bannersActiveOrDesactive",
        ]);
        $this->middleware("can:delete_banners")->only(["bannersDelete"]);
    }

    /**
     * Lists all available banners.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of banners or a JSON response in case of an error.
     */
    public function bannersList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_BANNERS;
            $parameters = $this->crudParameterService->findById(1);
            $listBanners = $this->crudBannerService->getAll(
                ["active" => EnumDecision::ACTIVE],
                ["order" => EnumOrderBy::ASC]
            );
            $myDateTime = $this->crudBannerService->getNowDateTime();

            return view(
                "panel.pages.banners.banners_list",
                compact("pageTitle", "parameters", "myDateTime", "listBanners")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Filters banners based on request parameters.
     *
     * @param Request $request Object containing request parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered data.
     */
    public function bannersFilters(Request $request)
    {
        try {
            $listBanners = $this->crudBannerService->getAll(
                ["active" => $request->selFilterStatus],
                ["order" => EnumOrderBy::ASC]
            );
            $myDateTime = $this->crudBannerService->getNowDateTime();
            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.banners._partials._grid", compact("listBanners", "myDateTime"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_USERS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_USERS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Retrieves the history of a specific banner.
     *
     * @param int $bannersId ID of the banner.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with banner history data.
     */
    public function getBannersHistory($bannersId)
    {
        try {
            Utils::maxOptimizations();
            $bannerAudit = $this->crudLogAuditService->getAll(
                ["table_name" => "banners", "table_item_id" => $bannersId],
                ["created_at" => EnumOrderBy::DESC]
            );

            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.banners._includes.tbody_audit_modal", compact("bannerAudit"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                2,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                3,
                ViewsModules::PANEL_USERS,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Stores the order of banners.
     *
     * @param Request $request Object containing banner order data.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response confirming the update.
     */
    public function bannersStoreOrder(Request $request)
    {
        try {
            $bannersId = explode(",", $request->banner_ids);
            $this->crudBannerService->updateOrder($bannersId);

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            //Erro de BD
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            //Erro Geral
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Displays the maintenance view for banners.
     *
     * @param int $id Optional banner ID for editing.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the maintenance view or a JSON response in case of an error.
     */
    public function bannersMaintenance($id = 0)
    {
        try {
            $pageTitle = ViewsModules::PANEL_BANNERS;
            $subTitle = "Novo Cadastro";
            $parameters = $this->crudParameterService->findById(1);
            $banner = $this->rulesMaintenanceService->getRegisterMaintenance($this->crudBannerService, $id);
            $stores = $this->crudStoreService->getAll([], ["name" => EnumOrderBy::ASC]);
            $links = $this->crudLinkService->getAll([], ["name" => EnumOrderBy::ASC]);
            return view(
                "panel.pages.banners.banners_maintenance",
                compact("pageTitle", "subTitle", "parameters", "banner", "stores", "links")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Stores a new banner in the system.
     *
     * @param SaveBannersRequest $request Validated request containing banner data.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response confirming the creation.
     */

    public function bannersStore(SaveBannersRequest $request)
    {
        try {
            $order = $this->crudBannerService->defineOrder(1, "order");

            $date_start = $this->rulesDateTimeService->joinDateAndTime($request->date_start, $request->time_start);
            $date_end = $this->rulesDateTimeService->joinDateAndTime($request->date_end, $request->time_end);
            $image_desktop = $this->rulesFilesService->generateNameAndSaveFile(
                $request->file_desktop,
                "public/site/banners/"
            );
            $image_mobile = $this->rulesFilesService->generateNameAndSaveFile(
                $request->file_mobile,
                "public/site/banners/"
            );

            $this->crudBannerService->save([
                "name" => $request->name,
                "image_desktop" => $image_desktop,
                "image_mobile" => $image_mobile,
                "order" => $order,
                "is_schedule" => $request->is_schedule,
                "links_id" => $request->links_id,
                "date_start" => $date_start,
                "date_end" => $date_end,
                "active" => $request->active,
            ]);

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            //Erro de BD
            //Apaga image nova
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_desktop);
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_mobile);
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            //Erro Geral
            //Apaga image nova
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_desktop);
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_mobile);
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Updates an existing banner.
     *
     * @param UpdateBannersRequest $request Validated request containing updated banner data.
     * @param int $id ID of the banner to be updated.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response confirming the update.
     */
    public function bannersUpdate(UpdateBannersRequest $request, $id)
    {
        try {
            $this->crudBannerService->beginTransaction();
            $banner = $this->crudBannerService->findById($id);
            $imageDesktopOld = $banner->image_desktop;
            $imageMobileOld = $banner->image_mobile;
            $imageDesktopNew = $request->image_desktop;
            $imageMobileNew = $request->image_mobile;

            $date_start = $this->rulesDateTimeService->joinDateAndTime($request->date_start, $request->time_start);
            $date_end = $this->rulesDateTimeService->joinDateAndTime($request->date_end, $request->time_end);

            $imageDesktopNew = $this->rulesFilesService->generateNameAndSaveFile(
                $request->file_desktop,
                "public/site/banners/"
            );
            $imageMobileNew = $this->rulesFilesService->generateNameAndSaveFile(
                $request->file_mobile,
                "public/site/banners/"
            );

            $image_desktop = $this->rulesFilesService->defineNameUpdate($imageDesktopOld, $imageDesktopNew);
            $image_mobile = $this->rulesFilesService->defineNameUpdate($imageMobileOld, $imageMobileNew);

            $this->crudBannerService->update($id, [
                "name" => $request->name,
                "image_desktop" => $image_desktop,
                "image_mobile" => $image_mobile,
                "is_schedule" => $request->is_schedule,
                "links_id" => $request->links_id,
                "date_start" => $date_start,
                "date_end" => $date_end,
                "active" => $request->active,
            ]);

            //Apaga imagem desktop e mobile antiga, se houve atualização delas
            $this->rulesFilesService->deleteOldFile($imageDesktopOld, $imageDesktopNew, "public/site/banners/");
            $this->rulesFilesService->deleteOldFile($imageMobileOld, $imageMobileNew, "public/site/banners/");
            $this->crudBannerService->commitTransaction();

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            //Erro de BD
            //Apaga image nova
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_desktop);
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_mobile);
            $this->crudBannerService->rollBackTransaction();
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            //Erro Geral
            //Apaga image nova
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_desktop);
            $this->rulesFilesService->fileDelete("public/site/banners/" . $image_mobile);
            $this->crudBannerService->rollBackTransaction();
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Deletes a banner from the system.
     *
     * @param int $id ID of the banner to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response confirming the deletion.
     */

    public function bannersDelete($id)
    {
        try {
            $myDateTime = $this->crudBannerService->getNowDateTime();
            $banner = $this->crudBannerService->findById($id);
            $status = $banner->active;

            $this->rulesFilesService->fileDelete("public/site/banners/" . $banner->image_desktop);
            $this->rulesFilesService->fileDelete("public/site/banners/" . $banner->image_mobile);
            $this->crudBannerService->delete($id);
            $listBanners = $this->crudBannerService->getAll(["active" => $status]);

            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.banners._partials._grid", compact("listBanners", "myDateTime"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Activates or deactivates a banner.
     *
     * @param Request $request Object containing request parameters.
     * @param int $id ID of the banner.
     * @param int $decision Activation or deactivation decision.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response confirming the update.
     */
    public function bannersActiveOrDesactive(Request $request, $id, $decision = EnumDecision::ACTIVE)
    {
        try {
            $myDateTime = $this->crudBannerService->getNowDateTime();
            $newOrder = $this->crudBannerService->defineOrder($decision, "order");

            $this->crudBannerService->update($id, [
                "active" => $decision,
                "order" => $newOrder,
            ]);

            $listBanners = $this->crudBannerService->getAll(
                ["active" => $decision == 1 ? EnumStatus::INACTIVE : EnumStatus::ACTIVE],
                ["order" => EnumOrderBy::ASC]
            );
            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.banners._partials._grid", compact("listBanners", "myDateTime"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_BANNERS,
                Actions::UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_BANNERS,
                Actions::UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
