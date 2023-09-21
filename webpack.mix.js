const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    'resolve': {
        'alias': {
        'react': 'preact-compat',
        'react-dom': 'preact-compat',
        },
    },
})
.js('public/js/app.js', 'public/dist/js').react();




    mix
    .js('resources/js/create_campaign_script.js', 'public/js')
    .js('resources/js/edit_campaign_script.js', 'public/js')
    .js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).minify('public/assets/js/soft-ui-dashboard.js');

mix.sass('public/assets/scss/soft-ui-dashboard.scss', 'public/assets/css');

mix.sass('public/assets/scss/custom/custom-styles.scss', 'public/assets/css');
