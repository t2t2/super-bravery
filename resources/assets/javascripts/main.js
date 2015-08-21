// Install router
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// Create router
import { configRouter } from './routes'

var router = new VueRouter({
	history:            true,
	saveScrollPosition: true,
});

configRouter(router)

// Start router with app
import App from './app/index'

router.start(App, '#application')

// For debugging
global.router = router;