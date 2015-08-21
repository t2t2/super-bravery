require('dotenv').load();

var config = {}

config.publicDirectory = './public'
config.sourceDirectory = './resources'
config.publicAssets    = config.publicDirectory + '/assets'
config.sourceAssets    = config.sourceDirectory + '/assets'

config.watchOptions = {};
if(process.env.GULP_WATCH_POLLING) {
	config.watchOptions.usePolling = true;
	config.watchOptions.interval = 1000;
}

module.exports = config
