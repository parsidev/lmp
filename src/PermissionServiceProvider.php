<?php

namespace Parsidev\Permission;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Parsidev\Permission\Contracts\PermissionInterface as Permission;
use Parsidev\Permission\Contracts\RoleInterface as Role;
use Parsidev\Permission\Directives\PermissionDirectives;

/**
 * Class PermissionServiceProvider
 * @package Parsidev\Permission
 */
class PermissionServiceProvider extends ServiceProvider
{
    public function boot(PermissionRegistrar $permissionLoader)
    {
        $helpers = new Helpers();
        if ($helpers->isNotLumen()) {
            $this->publishes([
                __DIR__ . '/../config/permission.php' => $this->app->configPath() . '/permission.php',
            ], 'config');

            if (!class_exists('CreatePermissionTables')) {
                $timestamp = date('Y_m_d_His');
                $mFilePath = $this->app->databasePath() . "/migrations/{$timestamp}_create_permission_collections.php";
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_permission_collections.php.stub' => $mFilePath,
                ], 'migrations');
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CreateRole::class,
                Commands\CreatePermission::class,
            ]);
        }

        $this->registerModelBindings();

        $permissionLoader->registerPermissions();
    }

    public function register()
    {
        $helpers = new Helpers();
        if ($helpers->isNotLumen()) {
            $this->mergeConfigFrom(
                __DIR__ . '/../config/permission.php',
                'permission'
            );
        }

        $this->registerBladeExtensions();
    }

    protected function registerModelBindings()
    {
        $config = $this->app->config['permission.models'];

        $this->app->bind(Permission::class, $config['permission']);
        $this->app->bind(Role::class, $config['role']);
    }

    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $permissionDirectives = new PermissionDirectives($bladeCompiler);

            $permissionDirectives->roleDirective();
            $permissionDirectives->hasroleDirective();
            $permissionDirectives->hasanyroleDirective();
            $permissionDirectives->hasallrolesDirective();
        });
    }
}
