/**
 * @file
 * Exports Gulp "sass" task
 */

const autoprefixer = require('gulp-autoprefixer');
const glob = require('gulp-sass-glob');
const civicrmScssRoot = require('civicrm-scssroot')();
const cssmin = require('gulp-cssmin');
const gulp = require('gulp');
const postcss = require('gulp-postcss');
const postcssDiscardDuplicates = require('postcss-discard-duplicates');
const postcssPrefix = require('postcss-prefix-selector');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const stripCssComments = require('gulp-strip-css-comments');
const sourcemaps = require('gulp-sourcemaps');
const transformSelectors = require('gulp-transform-selectors');

const BOOTSTRAP_NAMESPACE = '#bootstrap-theme';
const OUTSIDE_NAMESPACE_REGEX = /^\.___outside-namespace/;

/**
 * Deletes the special class that was used as marker for styles that should
 * not be nested inside the bootstrap namespace from the given selector
 *
 * @param  {String} selector
 * @return {String}
 */
function removeOutsideNamespaceMarker(selector) {
  return selector.replace(OUTSIDE_NAMESPACE_REGEX, '');
}

function sassTask() {
  return civicrmScssRoot.update()
    .then(() => {
      gulp.src('scss/prospect.scss')
        .pipe(glob())
        .pipe(sourcemaps.init())
        .pipe(autoprefixer({
          cascade: false,
        }))
        .pipe(sass({
          outputStyle: 'compressed',
          includePaths: civicrmScssRoot.getPath(),
          precision: 10,
        }).on('error', sass.logError))
        .pipe(stripCssComments({ preserve: false }))
        .pipe(postcss([postcssPrefix({
          prefix: `${BOOTSTRAP_NAMESPACE} `,
          exclude: [/^body/, /page-civicrm-case/, OUTSIDE_NAMESPACE_REGEX],
        }), postcssDiscardDuplicates]))
        .pipe(transformSelectors(removeOutsideNamespaceMarker, { splitOnCommas: true }))
        .pipe(cssmin({ sourceMap: true }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('css/'));
    });
}

module.exports = sassTask;
