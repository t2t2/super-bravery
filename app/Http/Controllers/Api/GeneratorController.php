<?php

namespace t2t2\SuperBravery\Http\Controllers\Api;

use t2t2\SuperBravery\Http\Controllers\Controller;
use t2t2\SuperBravery\Riot\StaticData;

class GeneratorController extends Controller {

	/**
	 * @var StaticData
	 */
	private $static;

	/**
	 * @param StaticData $static
	 */
	function __construct(StaticData $static) {
		$this->static = $static;
	}

	/**
	 * Roll a random result for the player
	 */
	public function roll() {
		$champion = $this->rollChampion();


		return response()->json([
			'champion' => $champion,
		]);
	}

	protected function rollChampion() {
		$champions = $this->static->champions();
		$champ_key = array_rand($champions);

		return $champions[$champ_key];
	}
}