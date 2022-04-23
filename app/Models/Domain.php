<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'domain',
        'tenant_id'
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->domain = strtolower($model->domain);
        });
    }

    protected $dispatchesEvents = [
        'saving' => \App\Events\SavingDomain::class,
        'saved' => \App\Events\DomainSaved::class,
        'creating' => \App\Events\CreatingDomain::class,
        'created' => \App\Events\DomainCreated::class,
        'updating' => \App\Events\UpdatingDomain::class,
        'updated' => \App\Events\DomainUpdated::class,
        'deleting' => \App\Events\DeletingDomain::class,
        'deleted' => \App\Events\DomainDeleted::class,
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
