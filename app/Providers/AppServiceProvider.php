<?php

namespace t2t2\SuperBravery\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Lumen\Application;
use t2t2\SuperBravery\Validators\ChampionValidator;
use t2t2\SuperBravery\Validators\MapValidator;

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

	    // Register custom validators
	    $app->extend('validator', function (Factory $factory) {
			$factory->extend('map', MapValidator::class . '@validate');
			$factory->extend('champion', ChampionValidator::class . '@validate');
			$factory->extend('champion_array', ChampionValidator::class . '@validateArray');

		    return $factory;
	    });
    }
}
