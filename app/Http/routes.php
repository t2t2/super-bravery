<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Application;

$app->group(['namespace' => 't2t2\SuperBravery\Http\Controllers\Api', 'prefix' => 'api'], function (Application $app) {

	$app->post('roll', [
		'as'   => 'api.generator.roll',
		'uses' => 'GeneratorController@roll',
	]);

});

$app->group(['namespace' => 't2t2\SuperBravery\Http\Controllers'], function (Application $app) {

	$app->get('/config', ['as' => 'frontend.config', 'uses' => 'FrontendController@config']);

	$app->get('/', ['as' => 'frontend.serve', 'uses' => 'FrontendController@serve']);

	$frontendPaths = [
		'/build/{build}',
	];

	foreach($frontendPaths as $path) {
		$app->get($path, ['uses' => 'FrontendController@serve']);
	}
});
