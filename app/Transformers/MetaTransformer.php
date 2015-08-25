<?php

namespace t2t2\SuperBravery\Transformers;


use League\Fractal\TransformerAbstract;
use t2t2\SuperBravery\MetaState;

class MetaTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'champions',
		'items',
		'maps',
		'summoner_spells',
	];

	/**
	 * Return meta information
	 *
	 * @param MetaState $state
	 *
	 * @return array
	 */
	public function transform(MetaState $state) {
		return [
			'versions' => $state->static->version(),
		];
	}

	/**
	 * Include champions data
	 *
	 * @param MetaState $state
	 *
	 * @return \League\Fractal\Resource\Collection
	 */
	public function includeChampions(MetaState $state) {
		$champions = $state->static->champions();

		return $this->collection($champions, app(ChampionTransformer::class));
	}

	/**
	 * Include items data
	 *
	 * @param MetaState $state
	 *
	 * @return \League\Fractal\Resource\Collection
	 */
	public function includeItems(MetaState $state) {
		$items = $state->static->items();
		
		return $this->collection($items, app(ItemTransformer::class));
	}

	/**
	 * Include items data
	 *
	 * @param MetaState $state
	 *
	 * @return \League\Fractal\Resource\Collection
	 */
	public function includeMaps(MetaState $state) {
		$maps = $state->static->maps();

		return $this->collection($maps, app(MapTransformer::class));
	}


	/**
	 * Include summoner spells data
	 *
	 * @param MetaState $state
	 *
	 * @return \League\Fractal\Resource\Collection
	 */
	public function includeSummonerSpells(MetaState $state) {
		$spells = $state->static->summonerSpells();

		return $this->collection($spells, app(SummonerSpellTransformer::class));
	}
}