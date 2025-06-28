<?php

namespace App\Services\Common\Rules;

use stdClass;
use App\Services\BaseRulesService;

class RulesDateTimeService extends BaseRulesService
{
    /**
     * Juntar data e hora em uma única variável
     */
    public function joinDateAndTime($date = null, $time = null)
    {
        if (isset($date)) {
            if ($time != null) {
                $time = $time . ":00";
            }
            $combined_date_and_time = str_replace(
                "/",
                "-",
                trim(date("Y-m-d", strtotime(str_replace("/", "-", $date))) . " " . $time)
            );
            $date = date("Y-m-d H:i:s", strtotime($combined_date_and_time));
        }
        return $date;
    }
}
