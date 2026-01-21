<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Auth::viaRemember(function ($user) {
            return $user->load('role.permissions');
        });
    }
}
