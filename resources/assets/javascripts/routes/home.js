import Cookies from 'js-cookie'
import Vue from 'vue'

import champion from '../components/champion'
import item from '../components/item'
import summonerSpell from '../components/summonerSpell'
import generatorSettings from '../components/generator-settings'

import template from './home.html'

export default Vue.extend({
	template,

	components: {
		generatorSettings,
		champion,
		item,
		summonerSpell
	},

	ready: function () {
		this.isAgreed = Cookies.get('kappa');
	},
	data: function () {
		return {
			isAgreed:  	  false,
			showSettings: false,
			build:        false,
			gettingBuild: false,
			message:      '',
			settings: {
				map:       null,
				champions: [],
			}
		}
	},
	computed: {
		champions: function () {
			return this.$root.champions
		},
		items: function () {
			return this.$root.items
		}
	},
	methods: {
		setAgreed: function (event) {
			this.isAgreed = event.target.checked
			Cookies.set('kappa', this.isAgreed);
		},
		rollBuild: function () {
			this.message = ''
			
			// Summoners rift default if no map was set.
			if (!this.settings.map) {
				this.settings.map = 11;
			}
			
			// Assumes you have all champions if you don't set any.
			if (!this.settings.champions.length) {
				this.settings.champions = this.$root.champions;
			}
			
			// Ok to go
			this.gettingBuild = true
			this.showSettings = false

			this.$root.$http.post('/api/roll', this.settings).success((data) => {
				this.build = data
				this.gettingBuild = false
			}).error((data) => {
				this.message = data
			})
		},
		toggleSettings: function () {
			this.showSettings = !this.showSettings;
		},
		championName: function (id) {
			var champion = this.$root.champions[id];
			return champion ? champion.name : undefined;
		}
	}
});