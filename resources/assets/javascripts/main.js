import 'babel-core/polyfill'

// Install router
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'

Vue.use(VueRouter)
Vue.use(VueResource)

if(process.env.NODE_ENV !== 'production') {
	Vue.config.debug = true
}

// Configure vue-resources
Vue.http.options.root = document.body.getAttribute('data-base');
// Read XSRF token
import Cookies from 'js-cookie'
var xsrfToken = Cookies.get('XSRF-TOKEN')
if (xsrfToken) {
	Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURI(xsrfToken)
}

// Create router
import { configRouter } from './routes'

var router = new VueRouter({
	history:            true,
	saveScrollPosition: true,
});

configRouter(router)

// Delay (initial) page switching until base app config is loaded

router.beforeEach(function ({ next }) {
	if(router.app.isLoading) {
		// Waiting for loading data in app
		var unwatch = router.app.$watch('isLoading', function (newState) {
			if(!newState) {
				unwatch()
				Vue.nextTick(next);
			}
		})
	} else {
		next()
	}
})

// Start router with app
import App from './app/index'

router.start(App, '#application')

// For debugging
global.router = router;