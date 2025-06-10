<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    protected $table = "cities";

    public function states()
    {
        return $this->hasOne("App\Models\State", "id", "states_id");
    }
}
