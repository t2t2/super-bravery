<?php

namespace t2t2\SuperBravery\Transformers;


use t2t2\SuperBravery\Riot\StaticData;

class MapTransformer {

	/**
	 * @var StaticData
	 */
	protected $staticData;

	public function __construct(StaticData $staticData) {
		$this->staticData = $staticData;
	}

	/**
	 * @param $item
	 *
	 * @return array
	 */
	public function transform($item) {
		$strings = $this->staticData->languageStrings();
		$stringKey = 'Map' . $item['MapId'];

		// SummonersRiftNew (11) uses SummonersRift (1)
		if($stringKey == 'Map11') {
			$stringKey = 'Map1';
		}

		if(isset($strings[$stringKey])) {
			$map_name = $strings[$stringKey];
		} else {
			$map_name = $item['MapName'];
		}

		return [
			'id'   => $item['MapId'],
			'key'  => $item['MapName'],
			'name' => $map_name,
		];
	}

}