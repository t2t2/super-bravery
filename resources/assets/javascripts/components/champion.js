import Vue from 'vue'

import template from './champion.html'

export default Vue.extend({
	template,

	computed: {
		champion: function () {
			return this.$root.champions[this.championId]
		},
		name: function () {
			return this.champion ? this.champion.name : '';
		},
		image: function () {
			if (this.champion) {
				return this.$root.imageURL('champion', this.champion.image.full);
			} else {
				return '';
			}
		}
	},

	data: function () {
		return {}
	},

	props: {
		championId:  null,
		height: {
			type: Number,
			default: 32
		},
		width: {
			type: Number,
			default: 32
		}
	},
})