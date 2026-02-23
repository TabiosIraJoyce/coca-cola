<?php

namespace App\Providers;
use Carbon\Carbon;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.timezone' => 'Asia/Manila']);
        date_default_timezone_set('Asia/Manila');
    
        // Tell Carbon to NOT convert timezone again
        Carbon::setUtf8(false);
    }
}
