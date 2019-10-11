'use strict';

var gulp = require('gulp');

var sassTask = require('./gulp-tasks/sass.js');
var testTask = require('./gulp-tasks/karma-unit-test.js');

/**
 * Runs Karma unit tests
 */
gulp.task('test', gulp.series(testTask));

/**
 * Compiles civicase.scss under scss folder to CSS counterpart
 */
gulp.task('sass', sassTask);

/**
 * Runs sass and test task
 */
gulp.task('default', gulp.parallel('sass', 'test'));
