<?php
/*
 * Polyfill due to:
 *
 * maps endpoint being unavailable - 23.08.2015
 * crystal scar missing (#RIPdominion) - 23.08.2015
 *
 * Based on
 * http://ddragon.leagueoflegends.com/cdn/5.14.1/data/en_US/map.json
 * Matches game version 5.14.1
 */
return [
	'type'    => 'map',
	'version' => '5.14.1',
	'data'    => [
		'10' => [
			'MapName'               => 'NewTwistedTreeline',
			'MapId'                 => '10',
			'UnpurchasableItemList' => [
				'1062', '1063', '2043', '2044', '2042', '2044', '2038', '2039', '2037', '3186', '3126', '3207', '3031',
				'3184', '3112', '3073', '3007', '3008', '3029', '3026', '3180', '3089', '3157', '3138', '3141', '3041',
				'3154', '1058', '3075', '3270', '3280', '3255', '3250', '3260', '3265', '3275', '3072', '3083', '1076',
				'2049', '2045', '3176', '3132', '2051', '3128', '3043', '3048', '3023', '3340', '3341', '3342', '3350',
				'3351', '3352', '3361', '3362', '3363', '3364', '1074', '1075', '1038', '3139', '3166', '3167', '3168',
				'3169', '3171', '3175', '3405', '3406', '3407', '3408', '3409', '3410', '3411', '3412', '3413', '3414',
				'3415', '3416', '3345', '3450', '3451', '3452', '3453', '3454', '3205', '3207', '3508', '3460', '3204',
				'3207', '3208', '1080', '3206', '3209', '2040', '2048', '3056', '2053', '3123', '3131', '3005', '3512',
				'3188', '3417', '3285', '2055', '3185', '3187', '3159', '3106',
			],
			'image'                 => [
				'full'   => 'map10.png',
				'sprite' => 'map0.png',
				'group'  => 'map',
				'x'      => 0,
				'y'      => 0,
				'w'      => 48,
				'h'      => 48,
			],
		],
		'11' => [
			'MapName'               => 'SummonersRiftNew',
			'MapId'                 => '11',
			'UnpurchasableItemList' => [
				'1060', '1062', '1063', '3180', '3181', '3182', '3184', '3185', '3186', '3187', '2047', '1061', '2048',
				'3122', '3090', '3104', '3188', '3084', '3159', '2040', '3132', '2051', '3073', '3007', '3008', '3029',
				'3112', '3170', '3043', '3048', '3290', '1074', '1075', '1076', '3137', '3417', '3418', '3419', '3420',
				'3421', '3422', '3345', '3450', '3451', '3452', '3453', '3454', '3204', '3208', '2037', '2039', '3206',
				'3207', '3209', '3106', '3154', '3160', '3205', '1080', '3460', '3123', '3131', '3005', '3128', '3166',
				'3405', '3411', '3286', '3348',
			],
			'image'                 => [
				'full'   => 'map11.png',
				'sprite' => 'map0.png',
				'group'  => 'map',
				'x'      => 144,
				'y'      => 0,
				'w'      => 48,
				'h'      => 48,
			],
		],
		'12' => [
			'MapName'               => 'ProvingGroundsNew',
			'MapId'                 => '12',
			'UnpurchasableItemList' => [
				"3026", "3041", "3141", "3138", "1062", "1063", "2055", "3181", "3180", "2042", "3106", "3126", "3154",
				"2044", "2043", "3104", "3188", "3084", "2041", "2040", "2048", "3132", "3159", "3090", "2045", "2049",
				"3056", "3029", "3073", "3007", "3008", "3280", "3250", "3255", "3260", "3265", "3270", "3275", "3223",
				"3224", "3170", "3043", "3048", "3186", "3023", "3340", "3350", "3361", "3362", "3137", "3166", "3167",
				"3168", "3169", "3171", "3175", "3405", "3406", "3407", "3408", "3409", "3410", "3411", "3412", "3413",
				"3414", "3415", "3416", "3345", "3450", "3451", "3452", "3453", "3454", "1039", "3205", "3204", "3207",
				"3208", "1080", "3209", "3206", "3460", "2037", "2039", "1039", "3711", "3719", "3720", "3721", "3722",
				"3706", "3707", "3708", "3709", "3710", "3713", "3723", "3724", "3725", "3726", "3715", "3714", "3716",
				"3717", "3718", "2053", "3123", "3131", "3005", "3512", "3128", "3417", "3286", "3348"
			],
			'image'                 => [
				'full'   => 'map12.png',
				'sprite' => 'map0.png',
				'group'  => 'map',
				'x'      => 48,
				'y'      => 0,
				'w'      => 48,
				'h'      => 48,
			],
		],
	],
];

