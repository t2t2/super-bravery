<?php

return [
	/**
	 * Map to gamemode mapping (used for summoner spell choosing)
	 */
	'map_to_mode'   => [
		'8'  => 'ODIN',    // Crystal Scar
		'10' => 'CLASSIC', // Twisted Treeline
		'11' => 'CLASSIC', // Summoner's Rift
		'12' => 'ARAM',    // Howling Abyss
	],

	/**
	 * Items that shouldn't be available
	 */
	'removed_items' => [
		// Black Market Brawlers
		3924, 3433, 3911, 3150, 3431, 3434, 3745, 3430, 3744, 3829, 3652, 3844, 3841, 3840,
		// Black Market Brawlers - Teleport Boots
		3245, 1341, 1340, 1339, 1335, 1336, 1337, 1338,
		// Black Market Brawlers - Mercenaries
		3611, 3612, 3613, 3614, 3615, 3616, 3617, 3621, 3622, 3623, 3624, 3625, 3626,
	],

	/**
	 * Items that must be owned by the champion
	 */
	'must_own' => [
		112 => [ // Viktor - Perfect Hex core
			3198
		]
	],

	/**
	 * Maximum index for build names
	 */
	'build_name_max' => 12,
];