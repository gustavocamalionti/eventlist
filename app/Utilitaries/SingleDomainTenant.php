<?php

declare(strict_types=1);

namespace App\Utilitaries;

use Stancl\Tenancy\Contracts\Tenant;

// NOTA: AGUARDAR V4 de stancl/tenancy

/**
 * @property string|null $domain
 */
interface SingleDomainTenant extends Tenant {}
