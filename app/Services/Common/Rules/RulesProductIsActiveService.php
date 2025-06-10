<?php

namespace App\Services\Common\Rules;

use Carbon\Carbon;
use App\Services\Bases\BaseRulesService;

class RulesProductIsActiveService extends BaseRulesService
{
    public function productIsActive($product)
    {
        $now = Carbon::now();
        $date_start = $product->date_start;
        $date_end = $product->date_end;

        //Produto está inativo
        if ($product->active == 0) {
            return 0;
        } else {
            //Produto está ativo
            if (is_null($date_start) && is_null($date_end)) {
                return 1;
            } else {
                //Existe pelo menos uma data Nula
                if (is_null($date_start) || is_null($date_end)) {
                    //Quando a data de inicio é nula
                    if (is_null($date_start)) {
                        if ($now >= $date_end) {
                            return 0;
                        }

                        if ($now < $date_end) {
                            return 1;
                        }
                    }

                    //Quando a data final é nula
                    if (is_null($date_end)) {
                        if ($now >= $date_start) {
                            return 1;
                        }

                        if ($now < $date_start) {
                            return 0;
                        }
                    }
                } else {
                    //Quando nenhuma das datas são nulas
                    if ($now >= $date_start && $now < $date_end) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
            }
        }
    }
}
