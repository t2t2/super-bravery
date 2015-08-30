<?php

namespace t2t2\SuperBravery\Http\Controllers;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
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
	 * Generate config for the frontend
	 *
	 * @param CacheRepository $cache
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function config(CacheRepository $cache) {

		$response = $cache->remember('frontend.config', 5, function () {
			$manager = app(Manager::class);
			$metaState = app(MetaState::class);

			$manager->parseIncludes(['champions', 'items', 'maps', 'summoner_spells']);

			$state = new Item($metaState, new MetaTransformer(), 'state');

			return $manager->createData($state)->toArray();
		});

		return response()->json($response);
	}

}