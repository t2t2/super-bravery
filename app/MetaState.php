<?php

namespace t2t2\SuperBravery;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use t2t2\SuperBravery\Riot\StaticData;

class MetaState {

	/**
	 * @var ConfigRepository
	 */
	private $config;

	/**
	 * @var StaticData
	 */
	public $static;

	/**
	 * @param StaticData       $static
	 * @param ConfigRepository $config
	 */
	function __construct(StaticData $static, ConfigRepository $config) {
		$this->static = $static;
		$this->config = $config;

		// Make sure generator config is loaded
		app()->configure('generator');
	}

	/**
	 * Map to gamemode mappings
	 *
	 * @return array|string[]
	 */
	public function mapToModeMappings() {
		return $this->config->get('generator.map_to_mode');
	}
}