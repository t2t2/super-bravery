var gulp = require('gulp');

gulp.task('images', function () {
	var changed = require('gulp-changed'),
		config = require('../config/images'),
		imagemin = require('gulp-imagemin');


	return gulp.src(config.src)
		.pipe(changed(config.dest)) // Ignore unchanged files
		.pipe(imagemin()) // Optimize
		.pipe(gulp.dest(config.dest));
});