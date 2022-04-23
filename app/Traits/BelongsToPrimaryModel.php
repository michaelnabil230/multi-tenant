<?php

namespace App\Traits;

use App\Scopes\ParentModelScope;

trait BelongsToPrimaryModel
{
    abstract public function getRelationshipToPrimaryModel(): string;

    public static function bootBelongsToPrimaryModel()
    {
        static::addGlobalScope(new ParentModelScope);
    }
}
