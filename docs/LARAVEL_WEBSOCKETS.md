# Comprovacions

## Configuració SSL

### WebSocket connection to 'wss://iesebre.scool.test:6001/app/6f627646afb1261d5b50?protocol=7&client=js&version=4.3.1&flash=false' failed: WebSocket is closed before the connection is established. 

Consola Javascript a vegades surt aquest error. Fas un F5 i ja connecta

### Configuració SSL local Laravel Valet

Comproveu que existeixen els fitxers:

```
cat /home/sergi/.valet/Certificates/scool.test.crt
cat /home/sergi/.valet/Certificates/scool.test.key
```

a **config/websockets.conf''' es configuren aquests fitxers a través de variables entorn.

IMPORTANT: Els certificats es creen amb valet secure però també es poden eliminar amb valet unsecure comproveu que existeixen

# Troubleshooting. 

## Illuminate\\Broadcasting\\BroadcastException in PusherBroadcaster

# Client Laravel Echo per consola no connecta al servidor error ERR_SSL_VERSION_OR_CIPHER_MISMATCH 
WebSocket connection to 'wss://iesebre.scool.test:6001/app/6f627646afb1261d5b50?protocol=7&client=js&version=4.3.1&flash=false' failed: Error in connection establishment: net::ERR_SSL_VERSION_OR_CIPHER_MISMATCH

# Proxy configuration at Laravel Forge:

https://github.com/acacha/scool/tree/master/nginx

In my case:

```
/etc/nginx/ssl/scool.cat/412609
```

Ara Laravel Forge Let's encrypt certificates (generated using Laravel Forge) for the main site scool.cat. Is a multitenant app so I use a wildcard configuration *. scool.cat (SSL certificate is multidomain).

Very important (at least It was for me my main error) to understant that if we use Proxy nginx the security is added ONLY by nginx so our laravel web sockets daemon running at 6001 will be simple without SSL.

So my Laravel web sockets an Laravel Broadcasting related files are:

```
https://github.com/acacha/scool/blob/master/config/broadcasting.php
```

My configuration is a little more complicated because I've using tenants then I have 4 possible pusher connections:

For my main app (no tenant -> scool.cat)

- pusher: Configuration for Laravel Valet on local
- pusher_production: Configuration for Laravel Valet on production

For tenants (*.scool.cat p.ex. tenant1.scool.cat)
- pusher_tenant: Configuration for Laravel Valet on local
- pusher_tenant_production: Configuration for Laravel Valet on production

The most important think as noted is how I use a simple laravel web sockets server without SSL in production.

Web sockets configuration (https://github.com/acacha/scool/blob/master/config/websockets.php):

I use a custom Apps provider: https://github.com/acacha/scool/blob/master/app/LaravelWebSockets/ConfigAppProvider.php
It adds an app for main app and one app for every tenant depending on tenant table in database
SSL is configured using .env variables to use SSL on local with Laravel Valet but not in production
And finally Laravel Echo configuration on client side javascript:

```
https://github.com/acacha/scool/blob/master/resources/tenant_js/bootstrap.js
```

I used similar tactics than Laravel with csrf-token so I can use Laravel env info in Javascript because I want different Larave echo object in production and local:

```
if (window.tenant) {
  if (window.tenant.pusher_app_key) {
    if (window.env) {
      if (window.env.app_env) {
        if (window.env.app_env === 'local') {
          window.Echo = new Echo({
            broadcaster: 'pusher',
            key: window.tenant.pusher_app_key,
            wsHost: window.location.hostname,
            wsPort: process.env.MIX_PUSHER_PORT,
            wssPort: 6001,
            disableStats: true,
            encrypted: false
          })
        }
        if (window.env.app_env === 'production') {
          window.Echo = new Echo({
            broadcaster: 'pusher',
            key: window.tenant.pusher_app_key,
            wsHost: 'socket.scool.cat',
            disablestats: true,
            encrypted: true
          })
        }
      }
    }
  }
```
  
A lot of configuration depends on .env files so I put here relevant information:

.env local:

```
WEBSOCKETS_LOCAL_CERT=/home/sergi/.valet/Certificates/scool.test.crt
WEBSOCKETS_LOCAL_PK=/home/sergi/.valet/Certificates/scool.test.key
WEBSOCKETS_VERIFY_PEER=false
```

.env production:

```
WEBSOCKETS_LOCAL_CERT=
WEBSOCKETS_LOCAL_PK=
WEBSOCKETS_VERIFY_PEER=false
```

Also you could see full project code at:

```
https://github.com/acacha/scool
```
