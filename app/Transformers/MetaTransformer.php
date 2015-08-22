<?php

namespace t2t2\SuperBravery\Transformers;


use League\Fractal\TransformerAbstract;
use t2t2\SuperBravery\MetaState;

class MetaTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'champions'
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

	public function includeChampions(MetaState $state) {
		$champions = $state->static->champions();

		return $this->collection($champions, new ChampionTransformer());
	}

}