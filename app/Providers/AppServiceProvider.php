<?php

namespace App\Providers;


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
