import Vue from 'vue'

import template from './summonerSpell.html'

export default Vue.extend({
	template,

	computed: {
		summonerSpell: function () {
			return this.$root.summonerSpells[this.summonerSpellId];
		},
		name: function () {
			return this.summonerSpell ? this.summonerSpell.name : '';
		},
		image: function () {
			if (this.summonerSpell) {
				return this.$root.imageURL('summoner', this.summonerSpell.image.full);
			} else {
				return '';
			}
		}
	},

	data: function () {
		return {}
	},

	props: {
		summonerSpellId:  null,
		height: {
			type: Number,
			default: 48
		},
		width: {
			type: Number,
			default: 48
		}
	},
})