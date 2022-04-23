<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;

/**
 * @property-read Tenant $tenant
 */
trait BelongsToTenant
{
    public static $tenantIdColumn = 'tenant_id';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, BelongsToTenant::$tenantIdColumn);
    }

    public static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (!$model->getAttribute(BelongsToTenant::$tenantIdColumn) && !$model->relationLoaded('tenant')) {
                if (tenancy()->initialized) {
                    $model->setAttribute(BelongsToTenant::$tenantIdColumn, tenant()->getTenantKey());
                    $model->setRelation('tenant', tenant());
                }
            }
        });
    }
}
