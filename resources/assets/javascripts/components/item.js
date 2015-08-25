import Vue from 'vue'

import template from './item.html'

export default Vue.extend({
	template,

	computed: {
		item:  function () {
			return this.$root.items[this.itemId]
		},
		name:  function () {
			return this.item ? this.item.name : '';
		},
		image: function () {
			if (this.item) {
				return this.$root.imageURL('item', this.item.image.full);
			} else {
				return '';
			}
		}
	},

	data: function () {
		return {}
	},

	props: {
		itemId: null,
		height: {
			type:    Number,
			default: 48
		},
		width:  {
			type:    Number,
			default: 48
		}
	},
})