<?php

namespace App\Providers;

use App\Enums\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('create_product', fn($user) => $user->hasPermission(Permission::CREATE_PRODUCT));

    }
}
