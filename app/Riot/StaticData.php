<?php

namespace t2t2\SuperBravery\Riot;


use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Cache\Store as CacheStore;

class StaticData {

	/**
	 * Cache versions for 3 hours (in minutes)
	 *
	 * @var int
	 */
	static protected $versionCache = 180;

	/**
	 * Cache static data for 4 weeks (in minutes)
	 *
	 * @var int
	 */
	static protected $dataCache = 40320;

	/**
	 * @var CacheRepository|CacheStore
	 */
	protected $cache;

	/**
	 * @var ConfigRepository
	 */
	protected $config;

	/**
	 * Region which versions and data to use
	 *
	 * @var string
	 */
	protected $region;

	/**
	 * Cache of region's current versions
	 *
	 * @var string[]
	 */
	protected $versions;

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @param ConfigRepository $config
	 * @param CacheRepository  $cache
	 */
	function __construct(ConfigRepository $config, CacheRepository $cache) {
		$this->config = $config;
		$this->cache = $cache;

		$this->setRegion($config->get('riot.default_region'));
	}

	/**
	 * Sets current region
	 *
	 * @param $region
	 */
	public function setRegion($region) {
		$this->region = $region;
		$this->versions = null;
	}

	/**
	 * Get the current champions
	 *
	 * @return mixed
	 */
	public function champions() {
		$version = $this->version('champion');

		$key = 'riot.static.' . $this->region . '.champions.' . $version;
		$champions = $this->cache->remember($key, self::$dataCache, function () use ($version) {
			$response = $this->getClient()->staticRequest($this->region, 'champion', null, [
				'version'   => $version,
				'champData' => 'image,spells',
				'dataById'  => 'true',
			]);
			$champions_data = json_decode($response->getBody(), true);

			return $champions_data['data'];
		});

		return $champions;
	}

	/**
	 * Get the items
	 *
	 * @return mixed
	 */
	public function items() {
		$version = $this->version('item');

		$key = 'riot.static.' . $this->region . '.items.' . $version;
		$items = $this->cache->remember($key, self::$dataCache, function () use ($version) {
			$response = $this->getClient()->staticRequest($this->region, 'item', null, [
				'version'      => $version,
				'itemListData' => 'requiredChampion,gold,stats,image',
			]);
			$items_data = json_decode($response->getBody(), true);

			return $items_data['data'];
		});

		return $items;
	}

	/**
	 * Get the items
	 *
	 * @return mixed
	 */
	public function languageStrings() {
		$version = $this->version('language');

		$key = 'riot.static.' . $this->region . '.language.' . $version;
		$strings = $this->cache->remember($key, self::$dataCache, function () use ($version) {
			$response = $this->getClient()->staticRequest($this->region, 'language-strings', null, [
				'version' => $version,
			]);
			$strings_data = json_decode($response->getBody(), true);

			return $strings_data['data'];
		});

		return $strings;

	}

	/**
	 * Get the maps data.
	 * POLYFILLED!
	 *
	 * @return mixed
	 */
	public function maps() {
		$version = $this->version('map');

		$key = 'riot.static.' . $this->region . '.maps.' . $version;
		$maps = $this->cache->remember($key, self::$dataCache, function () use ($version) {
			// NOTE: Polyfilled!
			$maps = require(storage_path('polyfills/map.php'));

			return $maps['data'];
		});

		return $maps;

	}

	/**
	 * Get the summoner spells.
	 *
	 * @return mixed
	 */
	public function summonerSpells() {
		$version = $this->version('summoner');

		$key = 'riot.static.' . $this->region . '.summoner.' . $version;
		$maps = $this->cache->remember($key, self::$dataCache, function () use ($version) {
			$response = $this->getClient()->staticRequest($this->region, 'summoner-spell', null, [
				'version'   => $version,
				'dataById'  => 'true',
				'spellData' => 'modes,image',
			]);
			$items_data = json_decode($response->getBody(), true);

			return $items_data['data'];

		});

		return $maps;

	}

	/**
	 * Get the current versions for this region
	 *
	 * @param null $type
	 *
	 * @return string[]|string
	 */
	public function version($type = null) {
		if (!$this->versions) {
			$key = 'riot.static.' . $this->region . '.realm';
			$this->versions = $this->cache->remember($key, self::$versionCache, function () {
				$response = $this->getClient()->staticRequest($this->region, 'realm');
				$realm_info = json_decode($response->getBody(), true);

				return $realm_info['n'] + [
					'cdn'  => $realm_info['cdn'],
					'meta' => $realm_info['v'],
				];
			});
		}

		if ($type) {
			return $this->versions[$type];
		} else {
			return $this->versions;
		}
	}

	/**
	 * Get the request client
	 *
	 * @return Client
	 */
	protected function getClient() {
		if ($this->client) {
			return $this->client;
		}

		return $this->client = app(Client::class);
	}


}