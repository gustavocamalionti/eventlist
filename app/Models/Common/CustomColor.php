<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class CustomColor extends Model
{
    protected $table = "custom_colors";

    protected $fillable = [
        "primary_color",
        "secondary_color",
        "text_color",
        "background_primary_color",
        "background_secondary_color",
        "category_primary_color",
        "category_secondary_color",
    ];
}
