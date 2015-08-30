var gulp = require('gulp');
gulp.task('build:production', function (cb) {
	var gulpSequence = require('gulp-sequence');

	gulpSequence('clean', ['images'], ['sass', 'webpack:production'], 'rev', cb);
});

