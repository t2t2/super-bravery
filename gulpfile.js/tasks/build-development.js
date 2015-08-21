var gulp = require('gulp')

gulp.task('build:development', function (cb) {
	var gulpSequence = require('gulp-sequence')

	gulpSequence('clean', ['images'], ['sass', 'webpack:development'], cb)
});
