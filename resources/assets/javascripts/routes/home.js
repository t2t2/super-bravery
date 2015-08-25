import Cookies from 'js-cookie'
import Vue from 'vue'

import championComponent from '../components/champion'
import itemComponent from '../components/item'

import template from './home.html'

export default Vue.extend({
	template,

	components: {
		champion: championComponent,
		item: itemComponent
	},

	ready: function () {
		this.isAgreed = Cookies.get('kappa');
	},
	data: function () {
		return {
			isAgreed: false,
			showSettings: false
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
		toggleSettings: function (event) {
			event.preventDefault();
			this.showSettings = !this.showSettings;
		}
	}
});