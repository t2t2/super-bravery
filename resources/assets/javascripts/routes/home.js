import Cookies from 'js-cookie'
import Vue from 'vue'

import champion from '../components/champion'
import item from '../components/item'
import itemSet from '../components/item-set'
import summonerSpell from '../components/summonerSpell'
import generatorSettings from '../components/generator-settings'

import template from './home.html'

export default Vue.extend({
	template,

	components: {
		champion,
		generatorSettings,
		item,
		itemSet,
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
		buildUrl: function () {
			var data = new Array;
			for (var o in this.build) {
				data.push(this.build[o]);
			}
			
			return [].concat.apply([], data).join(':');
		}
	},
	methods: {
		setAgreed: function (event) {
			this.isAgreed = event.target.checked
			Cookies.set('kappa', this.isAgreed);
		},
		rollBuild: function () {
			this.message = null;
			
			// Summoners rift default if no map was set.
			if (!this.settings.map) {
				this.settings.map = 11;
			}
			
			// Assumes you have all champions if you don't set any.
			if (!this.settings.champions.length) {
				this.settings.champions = Object.keys(this.$root.champions).map(key => parseInt(key));
			}
			
			// Ok to go
			this.gettingBuild = true
			this.showSettings = false
			this.build = false

			this.$root.$http.post('/api/roll', this.settings).success((data) => {
				this.build = data
				this.gettingBuild = false
			}).error((data) => {
				if(typeof data == 'object') {
					this.message = data
				} else {
					this.message = { error: data };
				}
				this.gettingBuild = false
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