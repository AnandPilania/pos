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

mix
    /* CSS */
    .sass('resources/assets/sass/app.scss', 'public/css/pickitapps.app.css')
    .sass('resources/assets/sass/admin.scss', 'public/css/pickitapps.admin.css')

    .sass('resources/assets/sass/dashmix/themes/xeco.scss', 'public/css/themes/')
    .sass('resources/assets/sass/dashmix/themes/xinspire.scss', 'public/css/themes/')
    .sass('resources/assets/sass/dashmix/themes/xmodern.scss', 'public/css/themes/')
    .sass('resources/assets/sass/dashmix/themes/xsmooth.scss', 'public/css/themes/')
    .sass('resources/assets/sass/dashmix/themes/xwork.scss', 'public/css/themes/')

    /* JS */
    .js('resources/assets/js/laravel/app.js', 'public/js/laravel.app.js')
    .js('resources/assets/js/dashmix/app.js', 'public/js/pickitapps.app.js')
    .js('resources/assets/js/dashmix/admin.js', 'public/js/pickitapps.admin.js')

    /* Tools */
    /*
    .browserSync({
        proxy: "test.com:8000"
    })
    .disableNotifications()
*/
    /* Options */
    .options({
        processCssUrls: false
    });
