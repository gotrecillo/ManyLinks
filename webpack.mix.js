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

mix.js('resources/assets/app/index.js', 'public/js')
    .stylus('resources/assets/stylus/main.styl', 'public/css')
    .browserSync({
        proxy: 'nginx',
        host: 'manylinks',
        open: false
    })
    .sourceMaps()
    .version();
