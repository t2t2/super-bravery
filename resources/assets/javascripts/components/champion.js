import Vue from 'vue'

import template from './champion.html'

export default Vue.extend({
	template,

	computed: {
		champion: function () {
			if (this.$root.$data.config.champions) {
				return this.$root.$data.config.champions.find((element) => {
					if (this.championId) {
						return element.id == this.$data.championId;
					}
					if (this.championKey) {
						return element.key == this.$data.championKey;
					}
				}, this);
			}
			return undefined;
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
		championKey: String,
		championHeight: {
			type: Number,
			default: 32
		},
		championWidth: {
			type: Number,
			default: 32
		}
	},
})