var gulp = require('gulp');

gulp.task('clean', function (cb) {
	var del = require('del'),
		config = require('../config');

	del([
		config.publicAssets
	], cb);
});
