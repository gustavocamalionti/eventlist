<?php

namespace App\Services\Common\Rules;

use App\Services\Bases\BaseRulesService;

class RulesMaintenanceService extends BaseRulesService
{
    /**
     * A nossa aplicação possui a mesma view para situações de create ou update.
     * Portanto, precisamos aplicar a lógica abaixo para fixar qual é o nome apropriado da página
     * e definir quais tipos de dados retornaremos.
     */
    public function getRegisterMaintenance($crudService, $id)
    {
        $register = null;
        if ($id > 0) {
            $register = $crudService->findById($id);
        }
        return $register;
    }
}
