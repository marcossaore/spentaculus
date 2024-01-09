<?php

namespace App\Providers;

use App\Contracts\Services\User\CreateUserServiceContract;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class UserServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CreateUserServiceContract::class, UserService::class);
    }
}
