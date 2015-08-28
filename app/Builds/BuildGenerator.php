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
	 * @var Collection
	 */
	protected $items;

	/**
	 * @var int
	 */
	protected $map;

	/**
	 * @var StaticData
	 */
	protected $static;

	/**
	 * @var Collection
	 */
	protected $summoners;

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
	 * Generate a random build
	 *
	 * @return array
	 */
	public function generateBuild() {
		if (!$this->map || !count($this->championPool)) {
			throw new GeneratorException('Missing state in the generator');
		}

		$this->champion = $this->chooseChampion();
		$this->summoners = $this->chooseSummoners();
		$this->items = $this->chooseItems();

		return [
			'name'      => 'x9',
			'champion'  => $this->champion['id'],
			'items'     => $this->items->map(function($item) {
				return $item['id'];
			})->values(),
			'map'       => $this->map['MapId'],
			'summoners' => $this->summoners->map(function ($spell) {
				return $spell['id'];
			})->values(),
		];

	}

	/**
	 * Sets the map for generator
	 *
	 * @param int $map
	 */
	public function setMap($map) {
		$maps = $this->static->maps();
		$this->map = $maps[$map];
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
		$mode = $this->config->get('generator.map_to_mode')[$this->map['MapId']];

		$spells = new Collection($this->static->summonerSpells());
		$spells = $spells->filter(function ($spell) use ($mode) {
			return in_array($mode, $spell['modes']);
		});

		return $spells->random(2);
	}

	/**
	 * Choose random items
	 *
	 * @return Collection
	 */
	protected function chooseItems() {
		$items = $this->getGroupedItemsForMap();

		return new Collection([
			$this->chooseBoots($items),
			$this->chooseBoots($items),
			$this->chooseBoots($items),
			$this->chooseBoots($items),
			$this->chooseBoots($items),
			$this->chooseBoots($items),
		]);
		/*
		$boots = $this->chooseBoots($items);

		*/
	}


	/**
	 * Choose random boots to wear
	 *
	 * @param Collection $items
	 *
	 * @return array
	 */
	protected function chooseBoots(Collection $items) {
		return $items['Boots']->random();
	}

	/**
	 * Get grouped items
	 *
	 * @return Collection
	 */
	protected function getGroupedItemsForMap() {
		$items = new Collection($this->static->items());

		$removed = array_merge($this->map['UnpurchasableItemList'], $this->config->get('generator.removed_items'));

		// Remove items disabled for the map
		foreach ($removed as $itemID) {
			if (isset($items[$itemID])) {
				unset($items[$itemID]);
			}
		}

		$items = $items->filter(function ($item) {
			// Remove unpurchaseable items (If it's not a devourer item)
			if ((!isset($item['gold']) || !$item['gold']['purchasable']) &&
				!(isset($item['group']) && $item['group'] == 'JungleItems')
			) {
				return false;
			}

			// Remove consumeables
			if (isset($item['consumed']) && $item['consumed']) {
				return false;
			}

			// Remove items that aren't the last
			if (isset($item['into']) && count($item['into'])) {
				return false;
			}

			// Remove enchanted boots that are raw boots (wtf?)
			if (isset($item['group']) && starts_with($item['group'], 'Boots') &&
				(!isset($item['from']) || !count($item['from']))
			) {
				return false;
			}

			// Remove items that are for specific champions. If they take a slot they're added in set generation step
			if (isset($item['requiredChampion']) && $item['requiredChampion']) {
				return false;
			}

			return true;
		});

		$items = $items->groupBy(function ($item) {
			$group = isset($item['group']) ? $item['group'] : 'Finished';
			switch ($group) {
				case 'BootsHomeguard':
				case 'BootsDistortion':
				case 'BootsCaptain':
				case 'BootsAlacrity':
				case 'BootsFuror':
					return 'Boots';
					break;
				case 'GoldBase': // Gold income items
				case 'Finished':
					return 'Finished';
					break;
				default:
					return $group;
					break;
			}
		}, true);

		return $items;
	}

}