import Vue from 'vue'

import template from './template.html'

function keyBy(data, key) {
	return data.reduce((mapped, item) => {
		mapped[item[key]] = item
		return mapped
	}, {});
}

var defaultVersion = '5.16.1'

export default Vue.extend({
	template,

	data: function () {
		return {
			versions:       {
				cdn:         'http://ddragon.leagueoflegends.com/cdn',
				item:        defaultVersion,
				rune:        defaultVersion,
				mastery:     defaultVersion,
				summoner:    defaultVersion,
				champion:    defaultVersion,
				profileicon: defaultVersion,
				map:         defaultVersion,
				language:    defaultVersion,
				meta:        defaultVersion,
			},
			map_to_mode:    {},
			champions:      {},
			items:          {},
			maps:           {},
			summonerSpells: {},
			isLoading:      true,
			baseURL: '/',
		}
	},

	methods: {
		imageURL: function (type, file) {
			var folder = type;

			// remap
			if (type == 'summoner') {
				folder = 'spell'
			}

			return [
				this.$data.versions.cdn,
				this.$data.versions[type],
				'img',
				folder,
				file
			].join('/');
		}
	},

	ready: function () {
		this.baseURL = document.body.getAttribute('data-base')

		// Load static data from server
		this.$http.get(document.body.getAttribute('data-config')).success((configured) => {
			this.$data.versions = configured.data.versions
			this.$data.map_to_mode = configured.data.map_to_mode
			this.$data.champions = keyBy(configured.data.champions.data, 'id')
			this.$data.items = keyBy(configured.data.items.data, 'id')
			this.$data.maps = keyBy(configured.data.maps.data, 'id')
			this.$data.summonerSpells = keyBy(configured.data.summoner_spells.data, 'id')
			this.$data.isLoading = false

		});
	},
});