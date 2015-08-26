<?php

namespace t2t2\SuperBravery\Http\Controllers\Api;

use Illuminate\Http\Request;
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
	public function roll(Request $request) {
		$champion = $this->rollChampion();


		return response()->json([
			'name' => 'x9',
			'champion' => $champion['id'],
			'items' => [1036, 1038, 1339, 3196, 1337, 1332],
			'summoners' => [4, 7],
			'request' => $request->all(),
		]);
	}

	/**
	 * Choose a random champion
	 *
	 * @return mixed
	 */
	protected function rollChampion() {
		$champions = $this->static->champions();
		$champ_key = array_rand($champions);

		return $champions[$champ_key];
	}
}