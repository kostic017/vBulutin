let mix = require('laravel-mix');

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

mix.browserSync('forum41.local');

mix.sass('resources/assets/sass/app.scss', 'public/css/app.css');
mix.sass('resources/assets/sass/board/admin/style.scss', 'public/css/board-admin.css');
mix.sass('resources/assets/sass/board/public/style.scss', 'public/css/board-public.css');

mix.scripts([
    'resources/assets/js/constants.js',
    'resources/assets/js/functions.js',
    'resources/assets/js/sceditor.js',
    'resources/assets/js/events.js',
], 'public/js/script.js');

mix.scripts([
    'resources/assets/js/admin/positions.js',
    'resources/assets/js/admin/sections-table.js',
    'resources/assets/js/admin/force-category.js'
], 'public/js/admin.js');

mix.js('resources/assets/js/app.js', 'public/js/app.js');
