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
		var value = "; " + document.cookie;
		var parts = value.split("; kappa=");
		this.isAgreed = parts.length == 2 ? (parts.pop().split(";").shift() == 'true') : false;
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
			document.cookie = "kappa=" + this.isAgreed;
		},
		toggleSettings: function (event) {
			event.preventDefault();
			this.showSettings = !this.showSettings;
		}
	}
});