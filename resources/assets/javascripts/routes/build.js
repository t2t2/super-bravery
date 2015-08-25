import Vue from 'vue'

import championComponent from '../components/champion'
import itemComponent from '../components/item'
import summonerSpellComponent from '../components/summonerSpell'

import template from './build.html'

export default Vue.extend({
	template,
	
	components: {
		champion: championComponent,
		item: itemComponent,
		summonerspell: summonerSpellComponent
	},

	computed: {
		champion: function() {
			if(this.$root.$data.config.champions) {
				return this.$root.$data.config.champions.find((element) => {
					return element.id == this.$data.build.champion;
				}, this);
			}
			return undefined;
		}
	},
	
	data: function () {
		return {
			build: {
				name: 'x9 thresh',
				champion: 412,
				items: [1036, 1038, 1339, 3196, 1337, 1332],
				summonerSpells: [1, 2],
				skillOrder: [
					[true, true, false, false, true, false, false, true, true, false, false, false, false, false, false, false, false, false],
					[false, false, true, false, false, false, false, false, false, true, false, true, true, true, false, false, false, false],
					[false, false, false, true, false, false, true, false, false, false, false, false, false, false, true, false, true, true],
					[false, false, false, false, false, true, false, false, false, false, true, false, false, false, false, true, false, false],
				]
			}
		};
	},

});