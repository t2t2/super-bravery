import Vue from 'vue'

import template from './generator-settings.html'

import championSelect from './champion-select'

export default Vue.extend({
	template,

	computed: {
		maps: function () {
			return this.$root.maps;
		},
	},

	components: {
		championSelect,
	},

	data: function () {
		return {
		};
	},

	props: {
		settings: {
			'default': function () {
				return {
					map: null,
					champions: [],
				};
			},
			twoWay: true,
		},
	},
})