<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\LogLastLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            LogLastLogin::class,
        ],
    ];



    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
