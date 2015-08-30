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
			build:        null,
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
			if(this.build) {
				return this.$root.baseURL + '/build/' + this.build.code;
			} else {
				return '';
			}
		},
		buildSummary: function () {
			return this.championName(this.build.champion) 
			+ ' - ' + this.itemName(this.build.items[0])
			+ ', ' + this.itemName(this.build.items[1])
			+ ', ' + this.itemName(this.build.items[2])
			+ ', ' + this.itemName(this.build.items[3])
			+ ', ' + this.itemName(this.build.items[4])
			+ ', ' + this.itemName(this.build.items[5])
			+ ' - ' + this.summonerName(this.build.summoners[0])
			+ ', ' + this.summonerName(this.build.summoners[1]);
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
			this.build = null

			this.$root.$http.post('/api/roll', this.settings).success((response) => {
				this.build = response.data
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
		},
		itemName: function (id) {
			var item = this.$root.items[id];
			return item ? item.name : undefined;
		},
		summonerName: function (id) {
			var summoner = this.$root.summonerSpells[id];
			return summoner ? summoner.name : undefined;
		}
	}
});