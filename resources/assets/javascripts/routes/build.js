import Vue from 'vue'
import Base64 from 'js-base64'

import champion from '../components/champion'
import item from '../components/item'
import itemSet from '../components/item-set'
import summonerSpell from '../components/summonerSpell'

import template from './build.html'

export default Vue.extend({
	template,

	components: {
		champion,
		item,
		itemSet,
		summonerSpell
	},

	ready: function () {
		var s = Base64.Base64.decode(this.$route.params.payload);
		var data = s.split(':');
		
		this.build = {
			"name": data[0],
			"champion": data[1],
			"items": [data[2], data[3], data[4], data[5], data[6], data[7]],
			// data[8] == map id
			"summoners": [data[9], data[10]]
		};
	},

	data: function () {
		return {
			build: false
		}
	},
	
	
	methods: {
		championName: function (id) {
			var champion = this.$root.champions[id];
			return champion ? champion.name : undefined;
		}
	}

});