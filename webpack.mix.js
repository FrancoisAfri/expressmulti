const mix = require('laravel-mix');

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

mix.js('resources/js/bootstrap.js', 'public/app.js')
	.js('resources/js/app.js', 'public/js')
    .vue()
     .postCss('resources/css/app.css', 'public/css');


// mix.copyDirectory('resources/js/custom_components', 'public/js/custom_components');
mix.styles([
    'public/libs/flatpickr/flatpickr.min.css',
    'public/css/bootstrap-creative.min.css',
    'public/css/app.css',
    'public/css/app-creative.css',
    'public/css/app-creative.min.css'
], 'public/global.css');
