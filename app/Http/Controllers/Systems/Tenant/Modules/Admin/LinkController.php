<?php

namespace App\Http\Controllers\Panel;

#region Import Libraries
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;
use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumLinkType;
use App\Libs\Enums\EnumErrorsType;
#endregion

#region Import Requests
use App\Http\Requests\Panel\SaveLinksRequest;
use App\Http\Requests\Panel\UpdateLinksRequest;
#endregion

#region Import Services
use App\Services\Crud\CrudLinkService;
use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStateService;
use App\Services\Crud\CrudStoreService;
use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudParameterService;
use App\Services\Panel\Rules\RulesFilesService;
use App\Services\Panel\Rules\RulesMaintenanceService;
#endregion

#region Import Models
use App\Models\Link;
#endregion

#region Import Jobs
#endregion

/**
 * LinkController is responsible for managing link-related actions in the administration panel.
 */
class LinkController extends Controller
{
    #region variables
    protected $crudParameterService;
    protected $crudLinkService;
    protected $crudStoreService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $crudLogAuditService;

    protected $rulesArchivedService;
    protected $rulesFilesService;
    protected $rulesMaintenanceService;
    #endregion

    #region _construct
    /**
     * Constructor to initialize necessary services and set permission middlewares.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudLinkService $crudLinkService Service for managing links.
     * @param CrudStoreService $crudStoreService Service for handling stores.
     * @param CrudStateService $crudStateService Service for managing states.
     * @param CrudCitieService $crudCitieService Service for managing cities.
     * @param CrudLogAuditService $crudLogAuditService Service for handling audit logs.
     * @param RulesFilesService $rulesFilesService Service for file validation rules.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for handling maintenance rules.
     * @return void
     */
    public function __construct(
        CrudParameterService $crudParameterService,
        CrudLinkService $crudLinkService,
        CrudStoreService $crudStoreService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        CrudLogAuditService $crudLogAuditService,
        RulesFilesService $rulesFilesService,
        RulesMaintenanceService $rulesMaintenanceService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudLinkService = $crudLinkService;
        $this->crudStoreService = $crudStoreService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->rulesFilesService = $rulesFilesService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;

        $this->middleware("can:read_links")->only(["linksList", "linksFilters"]);
        $this->middleware("can:read_links_audit")->only(["getLinkHistory"]);
        $this->middleware("can:create_links")->only(["linksMaintenance", "linksStore"]);
        $this->middleware("can:update_links")->only(["linksMaintenance", "linksUpdate"]);
        $this->middleware("can:delete_links")->only(["linksDelete"]);
    }
    #endregion

    /**
     * Lists all available links.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of links or a JSON response in case of an error.
     */
    public function linksList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::PANEL_LINKS;
            $parameters = $this->crudParameterService->findById(1);

            return view("panel.pages.links.links_list", compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Filters and returns a list of links based on the provided request parameters.
     *
     * @param Request $request The request containing filter parameters.
     * @return \Illuminate\Http\JsonResponse JSON response with filtered data.
     */
    public function LinksFilters(Request $request)
    {
        #region content
        try {
            $query = Link::query();
            if ($request->selFilterStatus != 2) {
                $query->where("active", $request->selFilterStatus);
            }

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "store",
                2 => "title",
                4 => "slug",
                5 => "name",
                6 => "link_type",
                7 => "created_at",
                8 => "active",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("title", "ASC")->orderBy("created_at", "desc");
            }

            return DataTables::of($query)
                ->addColumn("actions", function ($item) {
                    return view("panel.pages.links._includes.actions_grid", compact("item"))->render();
                })
                ->addColumn("stores", function ($item) {
                    return $item->stores->name;
                })
                ->addColumn("slug", function ($item) {
                    return '<a href="' .
                        env("APP_URL") .
                        "/" .
                        $item->slug .
                        '">' .
                        env("APP_URL") .
                        "/" .
                        $item->slug .
                        "</a>";
                })
                ->addColumn("link_type", function ($item) {
                    if ($item->link_type == EnumLinkType::REDIRECT) {
                        return "LINK";
                    }

                    if ($item->link_type == EnumLinkType::FILE) {
                        return "ARQUIVO";
                    }
                })
                ->addColumn("name", function ($item) {
                    if ($item->link != null) {
                        return $item->link;
                    }
                    return $item->name;
                })
                ->addColumn("active", function ($item) {
                    return "<span class='badge " .
                        ($item->active ? "bg-success" : "bg-danger") .
                        "'>" .
                        ($item->active ? "Ativo" : "Inativo") .
                        "</span>";
                })
                ->addColumn("created_at", function ($item) {
                    return date("d/m/Y H:i:s", strtotime($item->created_at));
                })

                ->rawColumns(["actions", "slug", "active"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LINKS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LINKS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Retrieves the link's history (audit logs) based on the link ID.
     *
     * @param int $linkId The ID of the link to retrieve history for.
     * @return \Illuminate\Http\JsonResponse JSON response containing the link's audit history.
     */
    public function getLinkHistory($linkId)
    {
        #region content
        try {
            Utils::maxOptimizations();
            $linkAudit = $this->crudLogAuditService->getAll(
                ["table_name" => "links", "table_item_id" => $linkId],
                ["created_at" => EnumOrderBy::DESC]
            );

            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.links._includes.tbody_audit_modal", compact("linkAudit"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                2,
                ViewsModules::PANEL_LINKS,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                3,
                ViewsModules::PANEL_LINKS,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        }
        #endregion
    }

    /**
     * Provides maintenance (create/edit) for a specific link or a new link if ID is 0.
     *
     * @param int $id The ID of the link to edit (defaults to 0 for creating a new link).
     * @return \Illuminate\View\View Returns the maintenance view for links.
     */
    public function linksMaintenance($id = 0)
    {
        #region content
        try {
            $pageTitle = ViewsModules::PANEL_LINKS;

            $parameters = $this->crudParameterService->findById(1);
            $link = $this->rulesMaintenanceService->getRegisterMaintenance($this->crudLinkService, $id);
            $stores = $this->crudStoreService->getAll([], ["name" => EnumOrderBy::ASC]);
            $subTitle = "Novo Cadastro";
            if ($link != null) {
                $subTitle = $link->title;
            }

            return view(
                "panel.pages.links.links_maintenance",
                compact("pageTitle", "subTitle", "parameters", "link", "stores")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Stores a new link based on the provided request data.
     *
     * @param SaveLinksRequest $request The request containing data for saving the link.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function linksStore(SaveLinksRequest $request)
    {
        #region content
        try {
            $dataRequest = $request->all();
            $data = [];

            // If we are in a situation of adding a new item via modal, then we should remove the entire 'modal-link-' substring.
            // This will ensure that the save happens without any issues.
            $prefixName = "";
            if (isset($dataRequest["prefixNameLinks"])) {
                $prefixName = $dataRequest["prefixNameLinks"];
            }

            foreach ($dataRequest as $key => $value) {
                if (str_contains($key, $prefixName)) {
                    $data[str_replace($prefixName, "", $key)] = $value;
                }
            }

            $nameFileNew = $this->crudLinkService->saveLinkType($data);
            $this->crudLinkService->save([
                "name" => $nameFileNew,
                "link_type" => $data["link_type"],
                "link" => $data["link"],
                "title" => $data["title"],
                "slug" => $data["slug"],
                "stores_id" => $data["stores_id"],
                "active" => isset($data["active"]) ? $data["active"] : 1,
            ]);

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            //Erro de BD

            $this->crudLinkService->deleteLinkType($data, "public/site/files/");
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LINKS,
                Actions::SAVE_OR_UPDATE,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            //Erro Geral

            $this->crudLinkService->deleteLinkType($data, "public/site/files/");
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LINKS,
                Actions::SAVE_OR_UPDATE,
                $e->getCode(),
                $e->getMessage()
            );
        }
        #endregion
    }

    /**
     * Updates an existing link based on the provided request data.
     *
     * @param UpdateLinksRequest $request The request containing the updated data for the link.
     * @param int $id The ID of the link to be updated.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function linksUpdate(UpdateLinksRequest $request, $id)
    {
        #region content
        try {
            $link = $this->crudLinkService->findById($id);
            $isFixed = $link->is_fixed;
            $nameFileOld = $link->name;
            $nameFileNew = $this->crudLinkService->updateLinkType($request);

            $this->rulesFilesService->deleteOldFile($nameFileOld, $nameFileNew, "public/site/files/");
            $this->crudLinkService->update($id, [
                "stores_id" => !$isFixed ? $request->stores_id : $link->stores_id,
                "title" => !$isFixed ? $request->title : $link->title,
                "slug" => !$isFixed ? $request->slug : $link->slug,
                "link_type" => !$isFixed ? $request->link_type : $link->link_type,
                "name" => $nameFileNew,
                "link" => $request->link,
                "active" => !$isFixed ? $request->active : $link->active,
            ]);

            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            //Erro de BD
            $this->crudLinkService->deleteLinkType($request, "public/site/files/");

            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LINKS,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            //Erro Geral
            $this->crudLinkService->deleteLinkType($request, "public/site/files/");
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LINKS,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Deletes a link by its ID.
     *
     * @param int $id The ID of the link to delete.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function linksDelete($id)
    {
        #region content
        try {
            $link = $this->crudLinkService->findById($id);

            if ($link->is_fixed) {
                return response()->json([
                    "status" => 2,
                ]);
            }

            $link_antigo = $link->name;
            $this->rulesFilesService->fileDelete("public/site/files/" . $link_antigo);
            $this->crudLinkService->delete($id);

            return response()->json([
                "status" => 1,
            ]);
        } catch (QueryException $e) {
            //ERRO DE BD
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LINKS,
                Actions::DELETE,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            //ERRO GERAL
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LINKS,
                Actions::DELETE,
                $e->getCode(),
                $e->getMessage()
            );
        }
        #endregion
    }
}
