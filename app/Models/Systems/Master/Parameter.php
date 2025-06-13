<?php

namespace App\Models\Systems\Master;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $table = "parameters";
    protected $guarded = ["_token", "id"];

    protected $fillable = [
        "official_site",
        "facebook_link",
        "instagram_link",
        "page_title",
        "vouchers_price",
        "vouchers_limit",
        "apicep",
        "email_contact",
        "gateway_id",
    ];
    public $timestamps = false;
}
