<?php

namespace t2t2\SuperBravery\Transformers;


class SummonerSpellTransformer {
	public function transform($item) {
		return [
			'id' => $item['id'],
			'key' => $item['key'],
			'name' => $item['name'],
			'description' => $item['description'],
			'image' => [
				'full' => $item['image']['full'],
			],
			'modes' => $item['modes'],
		];
	}
}