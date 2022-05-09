
var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

gulp.task('sass', function () {
    return gulp.src('./assets/sass/*.sass')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./dist/css'));
});

gulp.task('uglifycss', function () {
    return gulp.src('./dist/css/*.css')
        .pipe(uglifycss({
            "uglyComments": true
        }))
        .pipe(gulp.dest('./dist/css'));
});

gulp.task('concat', function() {
  return gulp.src('./assets/js/**/*.js')
    .pipe(concat('all.js'))
    .pipe(gulp.dest('./dist/js'));
});

gulp.task('uglify', function () {
    return gulp.src('./dist/js/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js'));
});

gulp.task('watch', function() {
    gulp.watch('./assets/sass/*.sass', gulp.series('sass', 'uglifycss'));
    gulp.watch('./assets/js/**/*.js', gulp.series('concat', 'uglify'));
});

gulp.task('default', gulp.series('watch'));
