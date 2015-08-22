import Vue from 'vue'

import championComponent from '../components/champion'

import template from './home.html'

export default Vue.extend({
	template,

	components: {
		champion: championComponent
	},

	ready: function () {
	}
});