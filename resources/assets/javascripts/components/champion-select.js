import Vue from 'vue'

import template from './champion-select.html'

import champion from './champion'

export default Vue.extend({
	template,

	computed: {
		champions: function () {
			return this.$root.champions;
		}
	},

	components: {
		champion,
	},

	data: function () {
		return {
		}
	},
	
	methods: {
		isSelected: function (championId) {
			return this.selectedChampions.indexOf(championId) >= 0
		},
		toggleChampion: function (championId) {
			var index = this.selectedChampions.indexOf(championId)
			if(index >= 0) {
				this.selectedChampions.$remove(index)
			} else {
				this.selectedChampions.push(championId)
			}
		},
	},

	props: {
		selectedChampions: {
			'default': function () {
				return [];
			},
			twoWay: true,
		},
	},
})