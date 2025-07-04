<?php namespace App\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
    public function validateCpf($attribute, $value, $parameters)
    {
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
        } /*
        Etapa 2: Combinações como 00000000000 e 22222222222 embora
        não sejam cpfs reais resultariam em cpfs
        válidos.
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
        /*
        Etapa 3: Calcula e compara o
        primeiro dígito verificador.
        */
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
        Etapa 4: Calcula e compara o
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
    }

    protected function replaceCPF($message, $attribute, $rule, $parameters)
    {
        if (count($parameters) > 0) {
            return str_replace(":cpf", $parameters, $message);
        } else {
            return $message;
        }
    }

    public function validateCnpj($attribute, $value, $parameters)
    {
        $cnpj = $value;
        /*
        Etapa 1: Cria um array com apenas os digitos numéricos,
        isso permite receber o cnpj em diferentes
        formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00"
        etc...
        */
        $j = 0;
        for ($i = 0; $i < strlen($cnpj); $i++) {
            if (is_numeric($cnpj[$i])) {
                $num[$j] = $cnpj[$i];
                $j++;
            }
        }

        //Etapa 2: Conta os dígitos, um Cnpj válido possui 14 dígitos numéricos.
        if (count($num) != 14) {
            return false;
        }

        if ($j == 0) {
            return false;
        }

        /*
        Etapa 3: O número 00000000000 embora não seja um cnpj real resultaria
        um cnpj válido após o calculo dos dígitos verificares
        e por isso precisa ser filtradas nesta etapa.
        */
        if (
            $num[0] == 0 &&
            $num[1] == 0 &&
            $num[2] == 0 &&
            $num[3] == 0 &&
            $num[4] == 0 &&
            $num[5] == 0 &&
            $num[6] == 0 &&
            $num[7] == 0 &&
            $num[8] == 0 &&
            $num[9] == 0 &&
            $num[10] == 0 &&
            $num[11] == 0
        ) {
            return false;
        }
        //Etapa 4: Calcula e compara o primeiro dígito verificador.
        else {
            $j = 5;
            for ($i = 0; $i < 4; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i++) {
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
            if ($dg != $num[12]) {
                return false;
            }
        }
        //Etapa 5: Calcula e compara o segundo dígito verificador.
        if (!isset($isCnpjValid)) {
            $j = 6;
            for ($i = 0; $i < 5; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 5; $i < 13; $i++) {
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
            if ($dg != $num[13]) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    protected function replaceCNPJ($message, $attribute, $rule, $parameters)
    {
        if (count($parameters) > 0) {
            return str_replace(":cnpj", $parameters, $message);
        } else {
            return $message;
        }
    }

    public function validateIsZero($attribute, $value, $parameters)
    {
        $valor = str_replace(",", ".", $value);
        return (float) $valor > 0;
    }

    public function validateUniqueWith($attribute, $value, $parameters)
    {
        //verifica se foi passado o valor do ID
        $IdPassed = is_numeric($parameters[count($parameters) - 1]) ? array_pop($parameters) : 0;

        $request = request()->all();

        // $table is always the first parameter
        // You can extend it to use dots in order to specify: connection.database.table
        $table = array_shift($parameters);

        // Add current column to the $clauses array
        $clauses = [
            $attribute => $value,
        ];

        // Add the rest
        foreach ($parameters as $column) {
            if (isset($request[$column])) {
                $clauses[$column] = $request[$column];
            }
        }

        if ($IdPassed > 0) {
            // Query for existence.
            return !DB::table($table)->where($clauses)->where("id", "<>", $IdPassed)->exists();
        } else {
            // Query for existence.
            return !DB::table($table)->where($clauses)->exists();
        }
    }
}
