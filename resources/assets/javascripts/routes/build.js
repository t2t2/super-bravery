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
		summonerSpell: summonerSpellComponent
	},

	computed: {
	},

	data: function () {
		return {
		}
	},

});