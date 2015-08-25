import Vue from 'vue'

import template from './generate.html'

import generatorSettings from '../components/generator-settings'

export default Vue.extend({

	template,

	components: {
		generatorSettings
	},

	data: function () {
		return {
			build:        false,
			gettingBuild: false,
			message:      '',
			settings:     {
				map:       null,
				champions: [],
			},
			showSettings: true,
		};
	},

	methods: {
		rollBuild:      function () {
			this.message = ''
			// Validation
			if (!this.settings.map) {
				this.message = 'Choose a map'
				return
			}
			if (!this.settings.champions.length) {
				this.message = 'Choose at least one champion'
				return
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
	},

})