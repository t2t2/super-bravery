import Vue from 'vue'

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

	data: function () {
		return {
			build:   false,
			message: null,
		}
	},

	methods: {
		championName: function (id) {
			var champion = this.$root.champions[id];
			return champion ? champion.name : undefined;
		}
	},

	route: {
		data: function (transition) {
			var code = transition.to.params.payload;

			return this.$http.get('/api/build/' + code).success((response) => {
				return {
					build: response.data,
				};
			}).error((data) => {
				if (typeof data == 'object') {
					return {
						message: data,
					};
				} else {
					return {
						message: {error: data}
					};
				}
			});
		}
	},

});