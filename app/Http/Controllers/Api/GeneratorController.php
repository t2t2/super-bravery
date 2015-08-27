<?php

namespace t2t2\SuperBravery\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use t2t2\SuperBravery\Builds\BuildGenerator;
use t2t2\SuperBravery\Builds\GeneratorException;
use t2t2\SuperBravery\Http\Controllers\Controller;
use t2t2\SuperBravery\Riot\StaticData;

class GeneratorController extends Controller {

	/**
	 * Roll a random result for the player
	 */
	public function roll(Request $request, BuildGenerator $generator) {
		$this->validate($request, [ // rules
			'map'       => ['required', 'map'],
			'champions' => ['required', 'array', ['min', 1], 'champion_array'],
		]);

		$generator->setMap($request->get('map'));
		$generator->setChampionPool($request->get('champions'));

		try {
			$build = $generator->generateBuild();
		} catch(GeneratorException $e) {
			return response()->json(['error' => $e->getMessage()], 422);
		}

		return response()->json($build);
	}

}