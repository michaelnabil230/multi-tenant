<?php

namespace App\Traits;

trait ConvertsDomainsToLowercase
{
    public static function bootConvertsDomainsToLowercase()
    {
        static::saving(function ($model) {
            $model->domain = strtolower($model->domain);
        });
    }
}
