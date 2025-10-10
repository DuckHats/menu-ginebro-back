<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Dish;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use App\Policies\DishPolicy;
use App\Policies\MenuPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Menu::class => MenuPolicy::class,
        Order::class => OrderPolicy::class,
        Dish::class => DishPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
