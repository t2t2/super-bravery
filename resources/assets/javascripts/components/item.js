import Vue from 'vue'

import template from './item.html'

export default Vue.extend({
	template,

	computed: {
		item: function () {
			if (this.$root.$data.config.items) {
				return this.$root.$data.config.items.find((element) => {
					if (this.itemId) {
						return element.id == this.$data.itemId;
					}
					if (this.itemName) {
						return element.key == this.$data.itemName;
					}
				}, this);
			}
			return undefined;
		},
		name: function () {
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
		itemId:  null,
		itemName: String,
		itemHeight: {
			type: Number,
			default: 48
		},
		itemWidth: {
			type: Number,
			default: 48
		}
	},
})