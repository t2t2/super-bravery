<?php

namespace t2t2\SuperBravery\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use t2t2\SuperBravery\Builds\BuildGenerator;
use t2t2\SuperBravery\Builds\GeneratorException;
use t2t2\SuperBravery\Http\Controllers\Controller;
use t2t2\SuperBravery\Riot\StaticData;
use t2t2\SuperBravery\Transformers\BuildTransformer;

class GeneratorController extends Controller {

	/**
	 * Roll a random result for the player
	 *
	 * @param Request        $request
	 * @param BuildGenerator $generator
	 * @param Manager        $manager
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function roll(Request $request, BuildGenerator $generator, Manager $manager) {
		$this->validate($request, [ // rules
			'map'       => ['required', 'map'],
			'champions' => ['required', 'array', ['min', 1], 'champion_array'],
		]);

		$generator->setMap($request->get('map'));
		$generator->setChampionPool($request->get('champions'));

		try {
			$build = $generator->generateBuild();
		} catch (GeneratorException $e) {
			return response()->json(['error' => $e->getMessage()], 422);
		}

		$response = $manager->createData(new Item($build, app(BuildTransformer::class), 'build'))->toArray();

		return response()->json($response);
	}

	/**
	 * @param BuildGenerator $generator
	 * @param Manager        $manager
	 * @param                $code
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getBuild(BuildGenerator $generator, Manager $manager, $code) {
		if(!preg_match('/^[0-9a-zA-Z]+$/', $code)) {
			return response()->json(['error' => 'Invalid build code', 'code' => $code], 422);
		}

		try {
			$build = $generator->getBuildFromCode($code);
		} catch (GeneratorException $e) {
			return response()->json(['error' => $e->getMessage()], 422);
		}

		$response = $manager->createData(new Item($build, app(BuildTransformer::class), 'build'))->toArray();

		return response()->json($response);
	}
}