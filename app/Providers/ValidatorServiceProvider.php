<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app["validator"]->extend("cpf", function ($attribute, $value, $parameters) {
            $cpf = $value;
            /*
            Etapa 1: Cria um array com apenas os digitos numéricos,
            isso permite receber o cpf em diferentes formatos
            como "000.000.000-00", "00000000000", "000 000 000 00"
            */
            $j = 0;
            for ($i = 0; $i < strlen($cpf); $i++) {
                if (is_numeric($cpf[$i])) {
                    $num[$j] = $cpf[$i];
                    $j++;
                }
            }

            if ($j == 0) {
                return false;
            }

            /*
            Etapa 2: Conta os dígitos,
            um cpf válido possui 11 dígitos numéricos.
            */
            if (count($num) != 11) {
                return false;
            } /*
            Etapa 3: Combinações como 00000000000 e 22222222222 embora
            não sejam cpfs reais resultariam em cpfs
            válidos após o calculo dos dígitos verificares e
            por isso precisam ser filtradas nesta parte.
            */ else {
                for ($i = 0; $i < 10; $i++) {
                    if (
                        $num[0] == $i &&
                        $num[1] == $i &&
                        $num[2] == $i &&
                        $num[3] == $i &&
                        $num[4] == $i &&
                        $num[5] == $i &&
                        $num[6] == $i &&
                        $num[7] == $i &&
                        $num[8] == $i
                    ) {
                        return false;
                        break;
                    }
                }
            }
            if (!isset($isCpfValid)) {
                $j = 10;
                for ($i = 0; $i < 9; $i++) {
                    $multiplica[$i] = $num[$i] * $j;
                    $j--;
                }
                $soma = array_sum($multiplica);
                $resto = $soma % 11;
                if ($resto < 2) {
                    $dg = 0;
                } else {
                    $dg = 11 - $resto;
                }
                if ($dg != $num[9]) {
                    return false;
                }
            }
            /*
            Etapa 5: Calcula e compara o
            segundo dígito verificador.
            */
            if (!isset($isCpfValid)) {
                $j = 11;
                for ($i = 0; $i < 10; $i++) {
                    $multiplica[$i] = $num[$i] * $j;
                    $j--;
                }
                $soma = array_sum($multiplica);
                $resto = $soma % 11;
                if ($resto < 2) {
                    $dg = 0;
                } else {
                    $dg = 11 - $resto;
                }
                if ($dg != $num[10]) {
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        });

        $this->app["validator"]->replacer("cpf", function ($message, $attribute, $rule, $parameters) {
            if (count($parameters) > 0) {
                return str_replace(":cpf", $parameters, $message);
            } else {
                return $message;
            }
        });

        /**
         * Máximo de caracteres
         */
        $this->app["validator"]->extend("maxlength", function ($attribute, $value, $parameters) {
            return strlen($value) <= $parameters[0];
        });

        $this->app["validator"]->replacer("maxlength", function ($message, $attribute, $rule, $parameters) {
            if (count($parameters) > 0) {
                return str_replace(":maxlength", $parameters[0], $message);
            } else {
                return $message;
            }
        });

        /**
         * Minimo de caracteres
         */
        $this->app["validator"]->extend("minlength", function ($attribute, $value, $parameters) {
            return strlen($value) >= $parameters[0];
        });

        $this->app["validator"]->replacer("minlength", function ($message, $attribute, $rule, $parameters) {
            if (count($parameters) < $parameters[0]) {
                return str_replace(":minlength", $parameters[0], $message);
            } else {
                return $message;
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
