var gulp = require('gulp');

gulp.task('watch', function () {
	var images = require('../config/images'),
		sass = require('../config/sass'),
		watch = require('gulp-watch'),
		watchOptions = require('../config').watchOptions;

	watch(images.src, watchOptions, function () {
		gulp.start('images');
	});
	watch(sass.src, watchOptions, function () {
		gulp.start('sass');
	});
});