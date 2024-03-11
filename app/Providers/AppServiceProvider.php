<?php

namespace App\Providers;

use App\Models\User;
use App\Models\EventsServices;
use App\Channels\DatabaseChannel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		User::observe(\App\Observers\UserObserver::class);
		EventsServices::observe(\App\Observers\ServicesObserver::class);
        
		$this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
