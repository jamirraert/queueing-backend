<?php

namespace App\Providers;

use App\Services\Auth\Auth;
use App\Services\Auth\AuthGateway;
use App\Services\CounterOptions\CounterOptions;
use App\Services\CounterOptions\Gateway;
use App\Services\ServiceOptions\Gateway as GatewaySerivice;
use App\Services\ServiceOptions\ServiceOptionsGateway;
use App\Services\Staff\CounterServiceGateway;
use App\Services\Staff\Gateway as StaffGateway;
use App\Services\Queue\Gateway as GatewayQueue;
use App\Services\Queue\QueueGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthGateway::class, Auth::class);
        $this->app->bind(Gateway::class, CounterOptions::class);
        $this->app->bind(GatewaySerivice::class, ServiceOptionsGateway::class);
        $this->app->bind(StaffGateway::class, CounterServiceGateway::class);
        $this->app->bind(GatewayQueue::class, QueueGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
