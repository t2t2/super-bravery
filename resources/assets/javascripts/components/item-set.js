import Vue from 'vue'

import template from './item-set.html'

var mapMap = {
	8:  'CS',
	10: 'TT',
	11: 'SR',
	12: 'HA',
};

/**
 * Generates blocks for item set
 *
 * @param items
 * @returns {*|Array}
 */
function blocksGenerator(items) {
	var blocks = items.map((itemID, index) => {
		return {
			type:    'Item #' + (index + 1),
			recMath: true,
			items:   getItemTree.call(this, itemID).map(item => ({
				'id':    item.toString(),
				'count': 1,
			})),
		}
	});

	/*
	 * Space for any extra blocks to be added
	 */

	return blocks;
}

/**
 * Generates an array of items require to build last item
 *
 * @param itemID
 * @returns {Array}
 */
function getItemTree(itemID) {
	var items = []

	if (itemID in this.$root.items && this.$root.items[itemID].from) {
		items = this.$root.items[itemID].from.reduce((items, itemID) => {
			items.push.apply(items, getItemTree.call(this, itemID))
			return items
		}, items);
	}

	items.push(itemID);

	return items;
}

export default Vue.extend({
	template,

	computed: {
		itemSet: function () {
			return {
				title:    'Super Ultimate Bravery',
				type:     'custom',
				map:      mapMap[this.build.map],
				mode:     this.$root.map_to_mode[this.build.map],
				// Show above others
				priority: true,
				sortrank: 99,
				blocks:   blocksGenerator.call(this, this.build.items),
			}
		},

		itemSetURL: function () {
			// http://stackoverflow.com/a/18197511
			return window.URL.createObjectURL(new Blob([JSON.stringify(this.itemSet)], {type: 'application/json'}));
		},

		itemSetPaths: function () {
			var champKey = ''
			if (this.build && this.build.champion) {
				champKey = this.$root.champions[this.build.champion].key
			}

			return [
				[this.leaguePath, 'Config', 'Global', 'Recommended'].join(this.dirSep) + this.dirSep,
				[this.leaguePath, 'Config', 'Champions', champKey, 'Recommended'].join(this.dirSep) + this.dirSep,
			];
		}
	},

	data: function () {
		var leaguePath = 'Path to League of Legends';
		var dirSep = '/'

		// The only time where checking navigator.appVersion is a legit way to detect capabilities
		if (navigator.appVersion.indexOf("Win") != -1) {
			leaguePath = 'C:\\Riot Games\\League of Legends'
			dirSep = '\\'
		} else if (navigator.appVersion.indexOf("Mac") != -1) {
			leaguePath = 'League of Legends.app/Contents/LoL'
		}

		return {
			          dirSep,
			          leaguePath,
			showHelp: false
		}
	},

	methods: {
		toggleHelp: function () {
			this.showHelp = !this.showHelp;
		},
	},

	props: {
		build: Object,
	},
})