<?php

namespace t2t2\SuperBravery\Riot;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Psr\Http\Message\ResponseInterface;

class Client {

	/**
	 * @var ConfigRepository
	 */
	protected $config;

	/**
	 * @var string[]
	 */
	static public $staticMethods = [
		'champion'         => '1.2',
		'item'             => '1.2',
		'language-strings' => '1.2',
		'languages'        => '1.2',
		'map'              => '1.2',
		'mastery'          => '1.2',
		'realm'            => '1.2',
		'rune'             => '1.2',
		'summoner-spell'   => '1.2',
		'versions'         => '1.2',
	];

	/**
	 * @param ConfigRepository $config
	 */
	function __construct(ConfigRepository $config) {
		$this->config = $config;
		$this->guzzle = new GuzzleClient([
			'base_uri' => 'https://global.api.pvp.net/api/lol/',
		]);
	}

	/**
	 * Make a request for static resource
	 *
	 * @param      $region
	 * @param      $method
	 * @param null $id
	 *
	 * @return ResponseInterface
	 * @throws \Exception
	 */
	public function staticRequest($region, $method, $id = null, $params = []) {
		if (!array_key_exists($method, self::$staticMethods)) {
			throw new \Exception('Invalid Method');
		}

		$url = 'static-data/' . $region . '/v' . self::$staticMethods[$method] . '/' . $method;
		if (!is_null($id)) {
			$url .= '/' . $id;
		}

		$response = $this->guzzle->get($url, [
			'query' => $this->calculateQuery($params),
		]);

		return $response;
	}

	/**
	 * Calculate query params
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	protected function calculateQuery($params) {
		return $params + [
			'api_key' => $this->config->get('riot.api_key')
		];
	}
}