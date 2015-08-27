<?php

namespace t2t2\SuperBravery\Builds;


use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;
use t2t2\SuperBravery\Riot\StaticData;

class BuildGenerator {

	/**
	 * @var array
	 */
	protected $champion;

	/**
	 * @var array
	 */
	protected $championPool;

	/**
	 * @var ConfigRepository
	 */
	protected $config;

	/**
	 * @var int
	 */
	protected $map;

	/**
	 * @var StaticData
	 */
	protected $static;

	/**
	 * @var array
	 */
	protected $summoners;

	/**
	 * @param StaticData $static
	 * @param ConfigRepository $config
	 */
	function __construct(StaticData $static, ConfigRepository $config) {
		$this->static = $static;
		$this->config = $config;

		// Make sure generator config is loaded
		app()->configure('generator');
	}


	/**
	 * Generate a random build
	 *
	 * @return array
	 */
	public function generateBuild() {
		if(!$this->map || !count($this->championPool)) {
			throw new GeneratorException('Missing state in the generator');
		}

		$this->champion = $this->chooseChampion();
		$this->summoners = $this->chooseSummoners();

		return [
			'name'      => 'x9',
			'champion'  => $this->champion['id'],
			'items'     => [1036, 1038, 1339, 3196, 1337, 1332],
			'map'       => $this->map,
			'summoners' => $this->summoners->map(function ($spell) {
			    return $spell['id'];
			}),
		];

	}

	/**
	 * Sets the map for generator
	 *
	 * @param int $map
	 */
	public function setMap($map) {
		$this->map = $map;
	}

	/**
	 * Sets the champion pool
	 *
	 * @param array $championPool
	 */
	public function setChampionPool($championPool) {
		$this->championPool = $championPool;
	}

	/**
	 * Choose a random champion from person's champion pool
	 *
	 * @return array
	 */
	protected function chooseChampion() {
		$champion_id = $this->championPool[array_rand($this->championPool)];
		$champions = $this->static->champions();

		return $champions[$champion_id];
	}

	/**
	 * Choose summoners related to the map
	 *
	 * @return Collection
	 */
	protected function chooseSummoners() {
		$mode = $this->config->get('generator.map_to_mode')[$this->map];

		$spells = new Collection($this->static->summonerSpells());
		$spells = $spells->filter(function ($spell) use ($mode) {
			return in_array($mode, $spell['modes']);
		});

		return $spells->random(2);
	}
}