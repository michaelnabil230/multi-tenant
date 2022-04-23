<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Traits\BelongsToTenant;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!tenancy()->initialized) {
            return;
        }

        $builder->where($model->qualifyColumn(BelongsToTenant::$tenantIdColumn), tenant()->getTenantKey());
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withoutTenancy', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
