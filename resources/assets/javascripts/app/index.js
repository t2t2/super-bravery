import Vue from 'vue'

import template from './template.html'

export default Vue.extend({
	template,

	data: function () {
		return {
			config:    {
				versions:  {
					cdn: 'http://ddragon.leagueoflegends.com/cdn'
				},
				champions: []
			},
			isLoading: true,
		}
	},

	methods: {
		imageURL: function (type, file) {
			return [
				this.$data.config.versions.cdn,
				this.$data.config.versions[type],
				'img',
				type,
				file
			].join('/');
		},
	},

	ready: function () {
		// Load static data from server
		this.$http.get(document.body.getAttribute('data-config')).success((configured) => {
			this.$data.config.versions = configured.data.versions
			this.$data.config.champions = configured.data.champions.data
			this.$data.isLoading = false
		});

	},
});