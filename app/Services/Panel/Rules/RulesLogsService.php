<?php

namespace App\Services\Panel\Rules;

use stdClass;
use App\Libs\Enums\EnumLogs;
use App\Libs\Enums\EnumStatus;
use App\Services\Bases\BaseRulesService;
use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudLogErrorService;
use App\Services\Crud\CrudLogActionService;

class RulesLogsService extends BaseRulesService
{
    protected $crudLogErrorService;
    protected $crudLogAuditService;

    public function __construct(CrudLogAuditService $crudLogAuditService, CrudLogErrorService $crudLogErrorService)
    {
        $this->crudLogAuditService = $crudLogAuditService;
        $this->crudLogErrorService = $crudLogErrorService;
    }

    // /**
    //  * A nossa aplicação possui a mesma view para situações de Erros e Ações. No entanto,
    //  *  aplicar a lógica abaixo para fixar qual é o nome apropriado da página
    //  * e definir quais tipos de registros de auditoria enviaremos.
    //  */
    // public function getAuditLog($logShowTable, $columnOrder, $orderBy)
    // {
    //     $data = '';
    //     $namePartialsView = '';
    //     switch ($logShowTable) {
    //         case EnumLogs::Errors:
    //             $data = $this->crudLogErrorService->getAll(EnumStatus::ALL, $columnOrder, $orderBy)->take(350);
    //             $namePartialsView = '_log_errors_grid';
    //             break;

    //         case EnumLogs::Actions:
    //             $data = $this->crudLogActionService->getAll(EnumStatus::ALL, $columnOrder, $orderBy)->take(350);
    //             $namePartialsView = '_log_actions_grid';
    //             break;
    //     }
    //     $obj = new stdClass();
    //     $obj->namePartialsView = $namePartialsView;
    //     $obj->data = $data;
    //     return $obj;
    // }
}
