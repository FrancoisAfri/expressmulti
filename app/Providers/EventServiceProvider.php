<?php

namespace App\Providers;

use App\Events\UserloginHistory;
use App\Listeners\StoreloginHistory;
use App\Models\User;
use App\Observers\NewUserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendNewPatientRegistrationNotification::class
        ],
        'Illuminate\Auth\Events\Login' =>
            [
                'App\Listeners\LogSuccessfulLogin',
                'App\Listeners\LoginSuccessful'
            ],
        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\ResetPasswordListener'
        ],
        UserLoginHistory::class => [
            StoreLoginHistory::class,
        ]
        ,

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(NewUserObserver::class);
    }
}
