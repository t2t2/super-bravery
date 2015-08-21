<?php

if (!function_exists('asset')) {
	/**
	 * Generate a URL to a asset.
	 *
	 * @param  string $name
	 * @param  array  $parameters
	 *
	 * @return string
	 */
	function asset($path = '') {
		return (new Laravel\Lumen\Routing\UrlGenerator(app()))
			->asset($path);
	}
}
