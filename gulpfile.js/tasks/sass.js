var gulp = require('gulp');

gulp.task('sass', function () {
	var autoprefixer = require('gulp-autoprefixer'),
		sass = require('gulp-sass'),
		sourcemaps = require('gulp-sourcemaps'),
		config = require('../config/sass'),
		handleErrors = require('../lib/handleErrors');

	return gulp.src(config.src)
		.pipe(sourcemaps.init())
		.pipe(sass(config.settings))
		.on('error', handleErrors)
		.pipe(sourcemaps.write())
		.pipe(autoprefixer(config.autoprefixer))
		.pipe(gulp.dest(config.dest));

});