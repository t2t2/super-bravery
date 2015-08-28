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
			'items'     => $this->items->map(function ($item) {
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
		$all_items = $this->getGroupedItemsForMap();

		$boots = $this->chooseBoots($all_items);

		$items = new Collection();

		// If has smite
		if ($this->summoners->offsetExists(11)) {
			$items->push($this->chooseSmiteItem($all_items));
		}

		// If champion must have an item
		$must_own = $this->config->get('generator.must_own');
		if (array_key_exists($this->champion['id'], $must_own)) {
			$items_database = $this->static->items();

			foreach ($must_own[$this->champion['id']] as $item_id) {
				$items->push($items_database[$item_id]);
			}
		}

		// Fill remaining slots
		$count = 5 - $items->count();

		$items = $items->merge($this->chooseRandomItems($all_items, $count));

		// Shuffle again so prereq items get randomised
		/** @var Collection $items */
		$items = $items->shuffle();

		$items->prepend($boots);

		return $items;
	}


	/**
	 * Choose random boots to wear
	 *
	 * @param Collection $all_items
	 *
	 * @return array
	 */
	protected function chooseBoots(Collection $all_items) {
		return $all_items['Boots']->random();
	}

	/**
	 * Choose random jungle item
	 *
	 * @param Collection $all_items
	 *
	 * @return array
	 */
	protected function chooseSmiteItem(Collection $all_items) {
		return $all_items['JungleItems']->random();
	}

	/**
	 * Choose random items for the player
	 *
	 * @param Collection $all_items
	 * @param int        $count
	 *
	 * @return Collection|array[]
	 */
	protected function chooseRandomItems(Collection $all_items, $count) {
		do {
			/** @var Collection $items */
			$items = $all_items['Finished']->random($count);
			// Make sure there isn't more than 2 GoldBase items
			$valid = $items->filter(function ($item) {
			    return isset($item['group']) && $item['group'] == 'GoldBase';
			})->count() < 2;
		} while(!$valid);
		return $items;
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
			if ((isset($item['into']) && count($item['into'])) || (!isset($item['from']) || !count($item['from']))) {
				return false;
			}

			// Remove enchanted boots that are raw boots (wtf?)
			/* Removed by $item['from'] above
			if (isset($item['group']) && starts_with($item['group'], 'Boots') &&
				(!isset($item['from']) || !count($item['from']))
			) {
				return false;
			}
			*/

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