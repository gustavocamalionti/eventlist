<?php

namespace App\Services\Bases;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Libs\Enums\EnumOrderBy;
use Illuminate\Support\Facades\Auth;

class BaseRulesService
{
    /**
     * Resgatar usuário(objeto) autenticado
     */
    public function getUserAuth()
    {
        $user = Auth::user();
        return $user;
    }

    /**
     * Usada para ordenar um select(object) que precise adicionar mais do que uma coluna. Exemplo,
     * select * from ...... orderby table1.name asc, table2.name asc, table3.name, asc
     */
    public function orderCollection($array, $nameColumns, $order = EnumOrderBy::ASC)
    {
        $array_convert =
            $order == EnumOrderBy::DESC ? $array->sortBy($nameColumns)->reverse() : $array->sortBy($nameColumns);

        return $array_convert->values();
    }

    /**
     * Resgatar a data e hora atual, possibilitando personalizar o formato
     */
    public function getNowDateTime($format = "d/m/Y H:i:s")
    {
        return Carbon::now()->format($format);
    }

    /**
     * Extrair um dado específico do array, criando um novo array.
     */
    public function generateNewArrayByColumn($array, $column)
    {
        $newArray = [];
        foreach ($array as $data) {
            array_push($newArray, $data->$column);
        }

        return $newArray;
    }

    public function generateSlug($string = "")
    {
        return Str::slug($string);
    }

    /**
     * Filtra os registros por data
     */

    public function filterByDate(Request $request, $query)
    {
        $dateStart = null;
        if (!empty($request->date_start)) {
            list($monthStart, $yearStart) = explode("/", $request->date_start);
            $dateStart = "{$yearStart}-{$monthStart}-01 00:00:00";
        }

        $dateEnd = null;
        if (!empty($request->date_end)) {
            list($monthEnd, $yearEnd) = explode("/", $request->date_end);
            // Cria o primeiro dia do mês
            $end = new DateTime("{$yearEnd}-{$monthEnd}-01");
            $end->modify("last day of this month");
            $dateEnd = $end->format("Y-m-d") . " 23:59:59";
        }

        if ($dateStart && $dateEnd) {
            return $query->whereBetween("created_at", [$dateStart, $dateEnd]);
        } elseif ($dateStart) {
            return $query->where("created_at", ">=", $dateStart);
        } elseif ($dateEnd) {
            return $query->where("created_at", "<=", $dateEnd);
        }

        return $query;
    }
}
