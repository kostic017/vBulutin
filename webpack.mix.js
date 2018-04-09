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

mix.js('resources/assets/js/app.js', 'public/js/app.js')
    .js('resources/assets/js/admin/table.js', 'public/js/admin')
    .js('resources/assets/js/admin/positions.js', 'public/js/admin');

mix.scripts([
    'resources/assets/js/functions.js',
], 'public/js/common.js');

getFiles('resources/assets/sass/admin').forEach(function (filepath) {
    mix.sass('resources/assets/sass/admin/' + filepath, 'public/css/admin');
});

mix.sass('resources/assets/sass/app.scss', 'public/css/app.css');
