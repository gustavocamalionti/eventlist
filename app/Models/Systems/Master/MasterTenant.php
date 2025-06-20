<?php

namespace App\Models\Systems\Master;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class MasterTenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    protected $table = "tenants";
}
