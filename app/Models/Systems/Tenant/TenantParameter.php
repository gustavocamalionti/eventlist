<?php

namespace App\Models\Systems\Tenant;

use Illuminate\Database\Eloquent\Model;

class TenantParameter extends Model
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
