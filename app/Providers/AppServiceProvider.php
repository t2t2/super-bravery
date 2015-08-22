<?php

namespace t2t2\SuperBravery\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    /** @var Application $app */
	    $app = $this->app;

	    // Add riot config
	    $app->configure('riot');
    }
}
