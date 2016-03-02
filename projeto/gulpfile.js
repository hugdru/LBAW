var gulp = require('gulp'),
  sass = require('gulp-sass'),
  notify = require('gulp-notify'),
  bower = require('gulp-bower'),
  sourcemaps = require('gulp-sourcemaps'),
  uglify = require('gulp-uglify'),
  merge = require('merge-stream'),
  insert = require('gulp-insert'),
  rename = require("gulp-rename"),
  watch = require('gulp-watch'),
  batch = require('gulp-batch'),
  del = require('del'),
  sequence = require('run-sequence');

var bower = './bower_components';
var assets = './resources';
var output = './public';

var config = {
  bower_bootstrap_scss: bower + '/bootstrap-sass/assets/stylesheets',
  bower_bootstrap_fonts: bower + '/bootstrap-sass/assets/fonts',
  bower_bootstrap_js: bower + '/bootstrap-sass/assets/javascripts/bootstrap.js',
  bower_jquery: bower + '/jquery/dist',
  assets_scss: assets + '/scss',
  assets_js: assets + '/js',
  assets_scss_site_wide_file: 'global.scss',
  output_css: output + '/css',
  output_js: output + '/js',
  output_fonts: output + '/fonts',
  output_maps_relative_path: 'maps',
};

gulp.task('bower', function() {
  return bower().pipe(gulp.dest(config.bower));
});

gulp.task('bootstrap-fonts', function() {
  return gulp.src(config.bower_bootstrap_fonts + '/**/*.*')
    .pipe(gulp.dest(config.output_fonts));
});

gulp.task('bootstrap-js', function() {
  return gulp.src(config.bower_bootstrap_js)
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write(config.output_maps_relative_path))
    .pipe(gulp.dest(config.output_js));
});

gulp.task('bootstrap-scss', function() {
  var my_bootstrap_overrides_path = config.assets_scss + '/' + config.assets_scss_site_wide_file;

  return gulp.src(my_bootstrap_overrides_path)
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed',
      precision: 10,
      includePaths: [
        config.bower_bootstrap_scss
      ]
    })).on("error", notify.onError(function(error) {
      return "Error: " + error.message;
    }))
    .pipe(sourcemaps.write(config.output_maps_relative_path))
    .pipe(gulp.dest(config.output_css));
});

gulp.task('jquery', function() {

  var min_new_filename = 'jquery.js';
  var min_map_new_filename = 'jquery.js.map';

  var min = gulp.src(config.bower_jquery + '/jquery.min.js')
    .pipe(insert.append('\n/*# sourceMappingURL=' + config.output_maps_relative_path + '/' + min_map_new_filename + ' */'))
    .pipe(rename(min_new_filename))
    .pipe(gulp.dest(config.output_js));
  var min_map = gulp.src(config.bower_jquery + '/jquery.min.map')
    .pipe(rename(min_map_new_filename))
    .pipe(gulp.dest(config.output_js + '/' + config.output_maps_relative_path));

  return merge(min, min_map);
});

gulp.task('assets-js', function() {
  return gulp.src(config.assets_js + '/**/*.js')
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write(config.output_maps_relative_path))
    .pipe(gulp.dest(config.output_js));
});

gulp.task('assets-scss', function() {
  return gulp.src([config.assets_scss + '/**/*.scss', '!' + config.assets_scss + '/' + config.assets_scss_site_wide_file])
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(sourcemaps.write(config.output_maps_relative_path))
    .pipe(gulp.dest(config.output_css));
});

gulp.task('bootstrap-deps', ['jquery']);
gulp.task('bootstrap', ['bootstrap-scss', 'bootstrap-js', 'bootstrap-fonts', 'bootstrap-deps']);

gulp.task('incremental', function() {
  sequence('default', 'watch');
});

gulp.task('default', function() {
  sequence('clean', ['bootstrap', 'assets-scss', 'assets-js']);
});

gulp.task('watch', function() {
  watch(
    config.assets_scss + '/' + config.assets_scss_site_wide_file,
    batch(function(events, done) {
      gulp.start('bootstrap-scss', done);
    }));
  watch(
    [config.assets_scss + '/**/*.scss', '!' + config.assets_scss + '/' + config.assets_scss_site_wide_file], batch(function(events, done) {
      gulp.start('assets-scss', done);
    }));
  watch(
    config.assets_js + '/**/*.js',
    batch(function(events, done) {
      gulp.start('assets-js', done);
    }));
});

gulp.task('clean', function() {
  return del(output);
});
