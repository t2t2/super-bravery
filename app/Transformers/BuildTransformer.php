<?php

namespace t2t2\SuperBravery\Transformers;


use t2t2\SuperBravery\Builds\Build;

class BuildTransformer {

	/**
	 * Generate JSON response of the build
	 *
	 * @param Build $build
	 *
	 * @return array
	 */
	public function transform(Build $build) {

		return [
			'code'      => $build->code,
			'name'      => trans('build_names.' . $build->name),
			'champion'  => $build->champion['id'],
			'items'     => $build->items->map(function ($item) {
				return $item['id'];
			})->values(),
			'map'       => $build->map['MapId'],
			'summoners' => $build->summoners->map(function ($spell) {
				return $spell['id'];
			})->values(),
		];
	}

}