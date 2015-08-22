<?php

namespace t2t2\SuperBravery;


use t2t2\SuperBravery\Riot\StaticData;

class MetaState {

	/**
	 * @var StaticData
	 */
	public $static;

	/**
	 * @param StaticData $static
	 */
	function __construct(StaticData $static) {
		$this->static = $static;
	}
}