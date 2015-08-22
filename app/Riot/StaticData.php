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
	 * Get the current versions for this region
	 *
	 * @param null $type
	 *
	 * @return string[]|string
	 */
	public function version($type = null) {
		if (!$this->versions) {
			$this->versions = $this->cache->remember('riot.static.' . $this->region . '.realm', self::$versionCache,
				function () {
					$response = $this->getClient()->staticRequest($this->region, 'realm');
					$realm_info = json_decode($response->getBody(), true);

					return $realm_info['n'] + [
						'cdn'  => $realm_info['cdn'],
						'meta' => $realm_info['v'],
					];
				}
			);
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