<?php

return [

	/**
	 * API key for Riot'S API calls
	 */

	'api_key'        => env('RIOT_API_KEY'),
	/**
	 * Rate limits for Riot API calls
	 */

	'api_limits'     => [
		'10min' => env('RIOT_API_LIMITS_10MIN', 500),
		'10sec' => env('RIOT_API_LIMITS_10SEC', 10),
	],
	/**
	 * Default region
	 */

	'default_region' => env('RIOT_DEFAULT_REGION', 'euw'),

];