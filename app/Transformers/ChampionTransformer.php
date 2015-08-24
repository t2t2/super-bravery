<?php

namespace t2t2\SuperBravery\Transformers;


class ChampionTransformer {

	public function transform($champion) {
		return [
			'id' => $champion['id'],
			'key' => $champion['key'],
			'name' => $champion['name'],
			'image' => [
				'full' => $champion['image']['full'],
			],
			'spells' => $champion['spells']
		];
	}

}