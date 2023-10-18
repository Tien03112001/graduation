var gulp = require('gulp');
var rename = require('gulp-rename');
var replace = require('gulp-replace');
var argv = require('yargs').argv;
var fs = require('fs');
var path = require('path');

function getFolders(dir) {
  return fs.readdirSync(dir)
    .filter(function (file) {
      return fs.statSync(path.join(dir, file)).isDirectory();
    });
}

const capitalize = (s) => {
  if (typeof s !== 'string') return ''
  return s.charAt(0).toUpperCase() + s.slice(1)
}

var basePath = argv.basePath;
if (!basePath) {
  basePath = '';
}


var root = argv.root;
var source = argv.source;
var dest = argv.dest;

var sourceFolder = root + '\\' + source;

gulp.task('copymodule', function () {

  return gulp.src(path.join(sourceFolder, '/**/*'))
    .pipe(rename(function (file) {
      file.basename = file.basename.replace(new RegExp(source, 'g'), dest);
      file.dirname = file.dirname.replace(new RegExp(source, 'g'), dest);

    }))
    .pipe(replace(new RegExp(source, 'g'), dest))
    .pipe(replace(new RegExp(capitalize(source), 'g'), capitalize(dest)))
    .pipe(gulp.dest('./'));
})

// run command to copy module
// gulp copymodule --root=src/app/modules --source=role --dest=user

