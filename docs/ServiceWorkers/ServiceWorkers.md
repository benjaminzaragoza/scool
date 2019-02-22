
# Workbox

## Passos previs

Val la pena realitzar el codelab: https://developers.google.com/web/tools/workbox/guides/codelabs/webpack
per entendre com funciona workbox for webpack

## Installation

```
npm install workbox-webpack-plugin --save-dev
```

## Registre del service Worker

- [X] Component Vue ServiceWorker
- [X] Utilitzar onload per controlar quan es registra el service worker -> https://developers.google.com/web/tools/workbox/guides/service-worker-checklist
- [X] OCO AMB ONLOAD -> https://www.stevesouders.com/blog/2014/09/12/onload-in-onload/
  - [X] Utilitzar readyState: https://javascript.info/onload-ondomcontentloaded
- [ ] AddToHomeScreen
  - Amb workbox no cal fer res més ja que ja registre events fetch-> No cal creac un event fetch buit
    
## Webpack plugins

- [X] GenerateSW descartat: https://developers.google.com/web/tools/workbox/modules/workbox-webpack-pluginç
  - You want to precache files. Vull
  - You would like to use your service worker with other API's (e.g. Web Push). Vull
  
### InjectManifest

- [X] Opció utilitzada per que volem fer el nostre propi service worker però amb l'ajuda de workbox per a gestionar offline
- [ ] Crear un service worker codi font (public/src-sw.js) i webpack generarà un service worker durant l'execució a la carpeta public
   - [ ] workbox.precaching.precacheAndRoute(self.__precacheManifest) 
   - [ ] self.__precacheManifest és l'array de recursos que s'han de precachejar i serà omplert automàticament per workbox a partir de la 
   configuració que realitzem (expressions regulars per indicar els fitxers que volem fer precache)
         

#### Laravel mix configuration

- [ ] Modificar el fitxer webpack.mix.js
  - [X] Afegir const workboxPlugin = require('workbox-webpack-plugin')
  - [X] Canviar la configuració de webpack amb webpackConfig https://laravel.com/docs/5.7/mix#custom-webpack-configuration
  - [ ] Controlar on es guardaran els fitxers generats per workbox (sino van directes a l'arrel?): importsDirectory: 'service-worker' // have a dedicated folder for sw files
  - [ ] Un cop tot provat en local es pot activar només a producció amb if (mix.inProduction()) {

```  
  mix.webpackConfig({
          plugins: [
              new workboxPlugin.InjectManifest({
                  swSrc: 'public/sw-offline.js', // more control over the caching
                  swDest: 'sw.js', // the service-worker file name NO CAL POSAR public/ ja es fa per defecte!
                  importsDirectory: 'service-worker' // have a dedicated folder for sw files
              })
          ]
      })
```  
- [ ] Error amb els paths a laravel mix: https://github.com/JeffreyWay/laravel-mix/issues/1717

```
const replace = require( 'replace-in-file' );
const path = require( 'path' );
const publicDir = 'public/html';
...
mix.js( 'resources/js/app.js', 'js' )
 ...
        .then( () => replace.sync( {
            // FIXME:   Workaround for laravel-mix placeing '//*.js' at the begining of JS filesystem
            files: path.normalize( `${publicDir}/precache-manifest.js` ),
            from:  /\/\//gu,
            to:    '/',
        } ) )
```
  
Recursos:
- https://ctf0.wordpress.com/2018/07/14/laravel-and-pwa/
- https://developers.google.com/web/tools/workbox/modules/workbox-cli#injectmanifest

## TODO

- https://developers.google.com/web/tools/workbox/modules/workbox-broadcast-cache-update
- https://developers.google.com/web/tools/workbox/guides/advanced-recipes#provide_a_fallback_response_to_a_route
- Cache control NGINX?: https://developers.google.com/web/tools/workbox/guides/service-worker-checklist
- Background sync
- Google analytics

## OFFLINE SUPPORT ???

NO ENTENC: https://ctf0.wordpress.com/2018/07/14/laravel-and-pwa/

Offline Support
up until now we’ve done everything by the book, but what about serving the pages in offline mode ? what if we want to have a fallback offline-page in case the user was in offline mode and the visited page wasn’t cached ?

So to solve this we

create a route for the offline fallback view
1
Route::view('offline', 'offline');
next create the offline view
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Not-Found Offline</title>
</head>
<body>
    <div class="wrapper">
        <h1>Sorry the page you have requested is currently not available in offline-mode.</h1>
        <p>Please go online so you can view it.</p>
    </div>
</body>
</html>
```
and lastly add the “magic” logic to the sw-offline.js file
```
// pre-cache pages
workbox.precaching.precacheAndRoute([
  {
     url: 'offline', 
     revision: Date.now()
  }
])


/**
* save pages to cache on visit & serve when offline
* or if not cached then serve the "offline view"
*/
const customHandler = async (args) => {
    try {
        return await workbox.strategies.networkFirst({
            cacheName: 'pages',
            plugins: [
                new workbox.expiration.Plugin({
                    maxEntries: 20,
                    purgeOnQuotaError: true
                })
            ]
        }).handle(args) || caches.match('offline')
    } catch (error) {
        return caches.match('offline')
    }
}
 
const navigationRoute = new workbox.routing.NavigationRoute(customHandler, {
    // dont cache this urls
    blacklist: [
        new RegExp('/(login|register|password|auth)'),
        new RegExp('/admin')
    ]
})
 
workbox.routing.registerRoute(navigationRoute)
```

now each page the user visits will be cached but only after we fetch it from the server so the user can ALWAYS get an updated content & in case he/she dont have an internet connection then the cached item will be served.

also in case the page wasnt cached and no connection, then we fall back to the offline page which simply tells him to go online in order to view it.

