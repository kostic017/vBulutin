let fs = require('fs');
let mix = require('laravel-mix');

let getFiles = function (dir) {
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

getFiles('resources/assets/js/admin').forEach(function (filepath) {
    mix.js('resources/assets/js/admin/' + filepath, 'public/js/admin');
});

getFiles('resources/assets/sass/admin').forEach(function (filepath) {
    mix.sass('resources/assets/sass/admin/' + filepath, 'public/css/admin');
});

mix.js('resources/assets/js/app.js', 'public/js/app.js');
mix.sass('resources/assets/sass/app.scss', 'public/css/app.css');
