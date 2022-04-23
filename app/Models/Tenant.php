<?php


namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use Uuids;

    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dispatchesEvents = [
        'saving' => \App\Events\SavingTenant::class,
        'saved' => \App\Events\TenantSaved::class,
        'creating' => \App\Events\CreatingTenant::class,
        'created' => \App\Events\TenantCreated::class,
        'updating' => \App\Events\UpdatingTenant::class,
        'updated' => \App\Events\TenantUpdated::class,
        'deleting' => \App\Events\DeletingTenant::class,
        'deleted' => \App\Events\TenantDeleted::class,
    ];

    public function getTenantKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function createDomain($data): Domain
    {
        if (! is_array($data)) {
            $data = ['domain' => $data];
        }

        $domain = (new Domain)->fill($data);
        $domain->tenant()->associate($this);
        $domain->save();

        return $domain;
    }
}
