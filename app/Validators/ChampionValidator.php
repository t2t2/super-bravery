<?php

namespace t2t2\SuperBravery\Validators;

use t2t2\SuperBravery\Riot\StaticData;

class ChampionValidator {

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
	 * Check if champion is in the list of champions
	 *
	 * @param            $attribute
	 * @param            $value
	 * @param            $parameters
	 * @param array|null $champions
	 *
	 * @return bool
	 */
	public function validate($attribute, $value, $parameters) {
		return array_key_exists($value, $this->static->champions());
	}

	/**
	 * Check if an array of champions are all a champion.
	 * Optimised for array use
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateArray($attribute, $value, $parameters) {
		$champions = $this->static->champions();
		return !array_first($value, function ($key, $champion) use($champions) {
		    return !array_key_exists($champion, $champions);
		});
	}
}