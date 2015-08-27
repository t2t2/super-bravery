<?php

namespace t2t2\SuperBravery\Validators;

use t2t2\SuperBravery\Riot\StaticData;

class MapValidator {

	/**
	 * @var StaticData
	 */
	protected $static;

	/**
	 * @param StaticData $static
	 */
	function __construct(StaticData $static) {
		$this->static = $static;
	}

	/**
	 * Check if map is in the list of maps
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validate($attribute, $value, $parameters) {
		return array_key_exists($value, $this->static->maps());
	}
}