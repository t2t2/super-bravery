<?php

namespace t2t2\SuperBravery\Http\Controllers;


use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use t2t2\SuperBravery\MetaState;
use t2t2\SuperBravery\Transformers\MetaTransformer;

class FrontendController extends Controller {

	/**
	 * Serve frontend to the user
	 *
	 * @return \Illuminate\View\View
	 */
	public function serve() {

		return view('frontend');
	}

	/**
	 * @param Manager   $manager
	 * @param MetaState $metaState
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function config(Manager $manager, MetaState $metaState) {

		$manager->parseIncludes(['champions', 'items', 'maps', 'summoner_spells']);

		$state = new Item($metaState, new MetaTransformer(), 'state');

		$response = $manager->createData($state)->toArray();

		return response()->json($response);
	}

}