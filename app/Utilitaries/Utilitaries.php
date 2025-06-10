<?php

namespace App\Utilitaries;

use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

/**
 * Created by PhpStorm.
 * User: Marcelo
 * Date: 13/02/17
 * Time: 01:13
 */
class Utilitaries
{
    public static function IsNullOrEmpty($var)
    {
        return !isset($var) || empty($var);
    }

    public static function MascaraEmail($email)
    {
        /* Tratamento antes do arroba */

        //Posição do @
        $posArroba = strpos($email, "@");
        $antesArrobaMantem = [];

        if ($posArroba == 2 || $posArroba == 3) {
            $antesArrobaMantem = [0];
        } elseif ($posArroba > 3) {
            $antesArrobaMantem = [0, $posArroba - 1];
        }

        for ($i = 0; $i < $posArroba; $i++) {
            if (!in_array($i, $antesArrobaMantem)) {
                $email[$i] = "*";
            }
        }

        return $email;
    }

    public static function PrecisaAtualizarCadastro()
    {
        if (Auth::user()->imported == 1 && Auth::user()->updated == 0 && Auth::user()->roles->id == 0) {
            //Cadastrado em campanhas anteriores, mas NÃO se atualizou na atual
            return response()->json([
                "status" => 2,
                "msgerro" => "O usuário já participou de outra campanha.",
                "email" => Auth::user()->email,
                "email_mask" => Utilitaries::MascaraEmail(Auth::user()->email),
                "p" => Crypt::encrypt(Auth::user()->cpf),
            ]);
        } else {
            return response()->json(["status" => 1, "msgerro" => "Usuário Logado."]);
        }
    }

    public static function Date_PtBR_To_En($dateSql)
    {
        $ano = substr($dateSql, 6);
        $mes = substr($dateSql, 3, -5);
        $dia = substr($dateSql, 0, -8);
        return $ano . "-" . $mes . "-" . $dia;
    }

    public static function DateTime_PtBR_To_En($dateSql)
    {
        $ano = substr($dateSql, 6);
        $mes = substr($dateSql, 3, -5);
        $dia = substr($dateSql, 0, -8);
        $hora = substr($dateSql, 0, -8);
        $min = substr($dateSql, 0, -8);
        $seg = substr($dateSql, 0, -8);
        return $ano . "-" . $mes . "-" . $dia . " " . $hora . ":" . $min . ":" . $seg;
    }

    /**
     * Altera uma data para outro formato
     *
     * @param string $date String contendo a data a ser formatada
     * @param string $outputFormat Formato de saida
     * @throws Exception Quando não puder converter a data
     * @return string Data formatada
     * @author Hugo Ferreira da Silva
     */
    public static function parseDate($date, $outputFormat = "d/m/Y")
    {
        if ($date != null) {
            $formats = ["d/m/Y", "d/m/Y H", "d/m/Y H:i", "d/m/Y H:i:s", "Y-m-d", "Y-m-d H", "Y-m-d H:i", "Y-m-d H:i:s"];

            foreach ($formats as $format) {
                $dateObj = \DateTime::createFromFormat($format, $date);
                if ($dateObj !== false) {
                    break;
                }
            }

            if ($dateObj === false) {
                throw new \Exception("Invalid date:" . $date);
            }

            return $dateObj->format($outputFormat);
        } else {
            return null;
        }
    }

    public static function rangeWeek($datestr)
    {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        return [
            "start" => date("Y-m-d 00:00:00", strtotime("last sunday", $dt)),
            "end" =>
                date("N", $dt) == 7
                    ? date("Y-m-d 23:59:59", $dt)
                    : date("Y-m-d 23:59:59", strtotime("next saturday", $dt)),
        ];
    }

    // $cnpj = "11222333000199";
    // $cpf = "00100200300";
    // $cep = "08665110";
    // $data = "10102010";

    // echo mask($cnpj,'##.###.###/####-##');
    // echo mask($cpf,'###.###.###-##');
    // echo mask($cep,'#####-###');
    // echo mask($data,'##/##/####');

    public static function FormatMask($val, $mask)
    {
        $maskared = "";
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == "#") {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }
}
