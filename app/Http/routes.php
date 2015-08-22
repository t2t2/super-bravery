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

$app->get('/', 'FrontendController@serve');

$app->get('/test', function (\t2t2\SuperBravery\Riot\StaticData $static) {
	dump($static->version());
});
