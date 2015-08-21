var gulp = require('gulp')

gulp.task('webpack:development', function (callback) {
	var built = false,
		config = require('../config/webpack')('development'),
		logger = require('../lib/compileLogger'),
		watchConfig = require('../config').watchOptions,
		webpack = require('webpack');

	if (global.watch) {
		var watchOptions = {
			aggregateTimeout: 200,
		};
		if(watchConfig.usePolling) {
			watchOptions.poll = watchConfig.interval;
		}

		webpack(config).watch(watchOptions, function (err, stats) {
			logger(err, stats)
			// On the initial compile, let gulp know the task is done
			if (!built) {
				built = true;
				callback()
			}
		})
	} else {
		webpack(config, function (err, stats) {
			logger(err, stats)
			callback()
		})
	}
})
