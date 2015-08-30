<?php

if (!function_exists('asset')) {
	/**
	 * Generate a URL to a asset.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	function asset($path = '') {
		return (new Laravel\Lumen\Routing\UrlGenerator(app()))
			->asset($path);
	}
}

if (!function_exists('base_convert_arbitrary')) {

	/**
	 * Converts numbers from any base to any base
	 * Modified to allow up to base62
	 * @url http://stackoverflow.com/a/5302533
	 *
	 * @param $number
	 * @param $fromBase
	 * @param $toBase
	 *
	 * @return string
	 */
	function base_convert_arbitrary($number, $fromBase, $toBase) {
		$digits = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$length = strlen($number);
		$result = '';

		$nibbles = [];
		for ($i = 0; $i < $length; ++$i) {
			$nibbles[$i] = strpos($digits, $number[$i]);
		}

		do {
			$value = 0;
			$newlen = 0;
			for ($i = 0; $i < $length; ++$i) {
				$value = $value * $fromBase + $nibbles[$i];
				if ($value >= $toBase) {
					$nibbles[$newlen++] = (int) ($value / $toBase);
					$value %= $toBase;
				} else {
					if ($newlen > 0) {
						$nibbles[$newlen++] = 0;
					}
				}
			}
			$length = $newlen;
			$result = $digits[$value] . $result;
		} while ($newlen != 0);

		return $result;
	}
}