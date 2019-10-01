<?php

namespace Parsidev\Permission\Traits;

use Parsidev\Permission\PermissionRegistrar;

/**
 * Trait RefreshesPermissionCache
 * @package Parsidev\Permission\Traits
 */
trait RefreshesPermissionCache
{
    public static function bootRefreshesPermissionCache()
    {
        static::saved(function () {
            \app(\config('permission.models.permission'))->forgetCachedPermissions();
        });

        static::deleted(function () {
            \app(\config('permission.models.permission'))->forgetCachedPermissions();
        });
    }
}
