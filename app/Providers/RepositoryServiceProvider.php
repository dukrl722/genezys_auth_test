<?php

namespace App\Providers;

use App\Http\Repositories\AddressRepository;
use App\Http\Repositories\contracts\AddressRepositoryInterface;
use App\Http\Repositories\contracts\UserRepositoryInterface;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
