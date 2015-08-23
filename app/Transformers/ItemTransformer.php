<?php

namespace t2t2\SuperBravery\Transformers;


class ItemTransformer {

	public function transform($item) {
		return [
			'id' => $item['id'],
			'name' => $item['name'],
			'description' => $item['description'],
			//'requiredChampion' => $item['requiredChampion'],
			'image' => [
				'full' => $item['image']['full'],
			],
			//'stats' => $item['stats'],
			//'gold' => $item['gold']
		];
	}

}