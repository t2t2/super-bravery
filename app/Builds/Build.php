<?php

namespace t2t2\SuperBravery\Builds;


use Illuminate\Support\Collection;

class Build {

	/**
	 * @var int
	 */
	public $name;

	/**
	 * @var string
	 */
	public $code;

	/**
	 * @var array
	 */
	public $champion;

	/**
	 * @var Collection
	 */
	public $items;

	/**
	 * @var int
	 */
	public $map;

	/**
	 * @var Collection
	 */
	public $summoners;

}