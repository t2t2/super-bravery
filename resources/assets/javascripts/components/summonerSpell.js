import Vue from 'vue'

import template from './summonerSpell.html'

export default Vue.extend({
	template,

	computed: {
		summonerSpell: function () {
			if (this.$root.$data.config.summonerSpells) {
				return this.$root.$data.config.summonerSpells.find((element) => {
					if (this.summonerSpellId) {
						return element.id == this.$data.summonerSpellId;
					}
					if (this.summonerSpellKey) {
						return element.key == this.$data.summonerSpellKey;
					}
				}, this);
			}
			return undefined;
		},
		name: function () {
			return this.summonerSpell ? this.summonerSpell.name : '';
		},
		image: function () {
			if (this.summonerSpell) {
				return this.$root.imageURL('spell', this.summonerSpell.image.full);
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
		summonerSpellKey: String,
		summonerSpellHeight: {
			type: Number,
			default: 48
		},
		summonerSpellWidth: {
			type: Number,
			default: 48
		}
	},
})