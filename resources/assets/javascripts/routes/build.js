import Vue from 'vue'

import championComponent from '../components/champion'
import itemComponent from '../components/item'

import template from './build.html'

export default Vue.extend({
	template,
	
	components: {
		champion: championComponent,
		item: itemComponent
	},
	
	computed: {
		build: function () {
			return {
				name: 'x9 thresh',
				champion: this.$root.$data.config.champions.find((element) => {
					return element.id == 412;
				}, this),
				items: [1036, 1038, 1339, 3196, 1337, 1332],
				skillOrder: [
					[true, true, false, false, true, false, false, true, true, false, false, false, false, false, false, false, false, false],
					[false, false, true, false, false, false, false, false, false, true, false, true, true, true, false, false, false, false],
					[false, false, false, true, false, false, true, false, false, false, false, false, false, false, true, false, true, true],
					[false, false, false, false, false, true, false, false, false, false, true, false, false, false, false, true, false, false],
				]
			}
		}
	}
});