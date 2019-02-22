const workboxPlugin = require('workbox-webpack-plugin')
const mix = require('laravel-mix');
const replace = require( 'replace-in-file' );
const path = require( 'path' );
const publicDir = 'public/';

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

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/app-landing.js', 'public/js/app-landing.js')
   .sourceMaps()
   .combine([
       'resources/css/bootstrap.min.css',
       'resources/css/font-awesome.min.css',
       'resources/css/ionicons.min.css',
       'node_modules/admin-lte/dist/css/AdminLTE.min.css',
       'node_modules/admin-lte/dist/css/skins/_all-skins.css',
       'node_modules/icheck/skins/square/blue.css'
   ], 'public/css/all.css')
   .combine([
       'resources/css/bootstrap.min.css',
       'resources/css/pratt_landing.min.css'
   ], 'public/css/all-landing.css')
   // PACKAGE (ADMINLTE-LARAVEL) RESOURCES
   .copy('resources/img/*.*','public/img/')
   //VENDOR RESOURCES
   .copy('node_modules/font-awesome/fonts/*.*','public/fonts/')
   .copy('node_modules/ionicons/dist/fonts/*.*','public/fonts/')
   .copy('node_modules/bootstrap/fonts/*.*','public/fonts/')
   .copy('node_modules/admin-lte/dist/css/skins/*.*','public/css/skins')
   .copy('node_modules/admin-lte/dist/img','public/img')
   .copy('node_modules/admin-lte/plugins','public/plugins')
   .copy('node_modules/icheck/skins/square/blue.png','public/css')
   .copy('node_modules/icheck/skins/square/blue@2x.png','public/css');

mix.js('resources/tenant_js/app.js', 'public/tenant/js').then( () => {
  replace.sync( {
    // SEE: https://github.com/JeffreyWay/laravel-mix/issues/1717
    // FIXME:   Workaround for laravel-mix placeing '//*.js' at the begining of JS filesystem

    files: path.normalize( `${publicDir}/service-worker/precache-manifest.*.js` ),
    from:  /\/\//gu,
    to:    '/',
  } )
}).sourceMaps()
  .sass('resources/tenant_sass/app.scss', 'public/tenant/css');

if (mix.config.inProduction) {
  mix.version();
  mix.minify();
}

// if (mix.inProduction()) {
  mix.webpackConfig({
    plugins: [
      // Options: https://developers.google.com/web/tools/workbox/modules/workbox-webpack-plugin
      new workboxPlugin.InjectManifest({
        swSrc: 'public/src-sw.js', // more control over the caching
        swDest: 'sw.js', // the service-worker file name
        importsDirectory: 'service-worker', // have a dedicated folder for sw files
        globPatterns: ['**/*.{php}'],
        // templatedUrls: {
        //   '/': ['index.php']
        // }
      })
    ]
  })
// }


