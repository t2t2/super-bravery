export function configRouter(router) {

	router.map({
		'/': { // Home page
			component: function (resolve) {
				require(['./routes/home'], resolve)
			},
		},
		'/build': {
			component: function (resolve) {
				require(['./routes/build'], resolve)
			},
		},
		'/build/:payload': {
			component: function (resolve) {
				require(['./routes/build'], resolve)
			},
		},
	})

}