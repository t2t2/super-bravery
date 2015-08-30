<?php

namespace t2t2\SuperBravery\Builds;


use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;
use t2t2\SuperBravery\Riot\StaticData;

class BuildGenerator {

	/**
	 * Current schema to use for build codes
	 *
	 * @var int
	 */
	protected static $currentSchema = 1;
	/**
	 * Schemas available
	 *
	 * @var array
	 */
	protected static $schemas = [
		1 => ['name', 'map', 'champion', 'summoners', 'items'],
	];

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
	 * @return Build
	 */
	public function generateBuild() {
		if (!$this->map || !count($this->championPool)) {
			throw new GeneratorException('Missing state in the generator');
		}

		$build = new Build();
		$build->map = $this->map;

		$this->chooseChampion($build);
		$this->chooseSummoners($build);
		$this->chooseItems($build);
		$build->name = 0;
		$build->code = $this->generateBuildCode($build);

		return $build;
	}

	/**
	 * Choose a random champion from person's champion pool
	 *
	 * @return array
	 */
	protected function chooseChampion(Build $build) {
		$champion_id = $this->championPool[array_rand($this->championPool)];
		$champions = $this->static->champions();

		$build->champion = $champions[$champion_id];

		return $build;
	}

	/**
	 * Choose summoners related to the map
	 *
	 * @return Collection
	 */
	protected function chooseSummoners(Build $build) {
		$mode = $this->config->get('generator.map_to_mode')[$build->map['MapId']];

		$spells = new Collection($this->static->summonerSpells());
		$spells = $spells->filter(function ($spell) use ($mode) {
			return in_array($mode, $spell['modes']);
		});

		$build->summoners = $spells->random(2);

		return $build;
	}

	/**
	 * Choose random items
	 *
	 * @return Collection
	 */
	protected function chooseItems(Build $build) {
		$all_items = $this->getGroupedItemsForMap();

		$boots = $this->chooseBoots($all_items);

		$items = new Collection();

		// If has smite
		if ($build->summoners->offsetExists(11)) {
			$items->push($this->chooseSmiteItem($all_items));
		}

		// If champion must have an item
		$must_own = $this->config->get('generator.must_own');
		if (array_key_exists($build->champion['id'], $must_own)) {
			$items_database = $this->static->items();

			foreach ($must_own[$build->champion['id']] as $item_id) {
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

		$build->items = $items;

		return $build;
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
		} while (!$valid);

		return $items;
	}

	/**
	 * Generates code version of the build
	 *
	 * @param Build $build
	 *
	 * @return string
	 */
	public function generateBuildCode(Build $build) {
		$schema = static::$currentSchema;
		$parts = [$schema];

		foreach (static::$schemas[$schema] as $part) {
			switch ($part) {
				case 'name':
					array_push($parts, $build->name);
					break;
				case 'map':
					array_push($parts, $build->map['MapId']);
					break;
				case 'champion':
					array_push($parts, $build->champion['id']);
					break;
				case 'summoners':
					$values = $build->summoners->values();
					for ($i = 0; $i < 2; $i++) {
						array_push($parts, $values[$i]['id']);
					}
					break;
				case 'items':
					$values = $build->items->values();
					for ($i = 0; $i < 6; $i++) {
						array_push($parts, $values[$i]['id']);
					}
					break;
			}
		}

		$checksum = $this->getCheckSum($parts);
		array_push($parts, $checksum);

		// Turn into string where separator = 11base11
		$stringified = implode('a', $parts);

		return base_convert_arbitrary($stringified, 11, 62);
	}

	/**
	 * Generates build from given code
	 *
	 * @param $code
	 *
	 * @return Build
	 */
	public function getBuildFromCode($code) {
		$uncompressed = base_convert_arbitrary($code, 62, 11);
		$parts = explode('a', $uncompressed);

		// Check checksum
		$checksum = array_pop($parts);
		if ($checksum != $this->getCheckSum($parts)) {
			throw new GeneratorException('Invalid build code');
		}

		$schema = array_shift($parts);
		if (!array_key_exists($schema, static::$schemas)) {
			throw new GeneratorException('Invalid build code');
		}
		$build = new Build();

		foreach (static::$schemas[$schema] as $part) {
			switch ($part) {
				case 'name':
					$build->name = array_shift($parts);
					break;
				case 'map':
					$map_id = array_shift($parts);
					$maps = $this->static->maps();
					$build->map = $maps[$map_id];
					break;
				case 'champion':
					$champion_id = array_shift($parts);
					$champions = $this->static->champions();
					$build->champion = $champions[$champion_id];
					break;
				case 'summoners':
					$summoners = $this->static->summonerSpells();
					$build->summoners = new Collection();
					for ($i = 0; $i < 2; $i++) {
						$spell_id = array_shift($parts);
						$build->summoners[intval($spell_id)] = $summoners[$spell_id];
					}
					break;
				case 'items':
					$items = $this->static->items();
					$build->items = new Collection();
					for ($i = 0; $i < 6; $i++) {
						$item_id = array_shift($parts);
						$build->items->push($items[$item_id]);
					}
					break;
			}
		}
		$build->code = $code;

		return $build;
	}

	/**
	 * Generates a checksum for the parts, converted to base10
	 *
	 * @param $parts
	 *
	 * @return string
	 */
	protected function getCheckSum($parts) {
		$hash = md5(implode(':', $parts) . config('app.key'));

		return base_convert_arbitrary($hash, 16, 10);
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

}