# CREDENTIALS API

- https://medium.com/dev-channel/sign-in-on-the-web-credential-management-api-and-best-practices-d21aed14b6fe
- https://developers.google.com/web/fundamentals/security/credential-management/
- app exemple: https://credential-management-sample.appspot.com/
- https://developers.google.com/web/fundamentals/security/credential-management/retrieve-credentials
- https://polykart-credential-payment.appspot.com/account

# USER TYPE

- [X] Crear el tipus becaris -> Afegir dídac i Sergio a becaris
- [X] Posar el tipus conserges als conserges als helpers
- [X] Posar el tipus administrative a les administratives
- [X] Posar el user type profe a tots els professors (esta fet)

# UPGRADE TO LARAVEL MIX 4.0

- https://laravel-mix.com/docs/4.0/upgrade
- https://laravel-news.com/laravel-mix-4-released
- [X] a app.js canviats tots els requires per imporst
- [X] Canviar la primera línia del fitxer webpack.mix.js a const mix = require('laravel-mix');

# Error page 404

NO és correcte surt la de AdminLTE, vegeu:
- https://iesebre.scool.test/users/99999
- [ ] Com es pot fer una custom Error Page per a cada tenant?

# ONLINE USERS

FRONTEND:
- [ ] Crear component per mostrar els usuaris que estan online
 - [ ] Opció refresh
- [ ] Mostrar al perfil d'un usuari si està online o no
- [X] Llista d'usuaris -> mostrar columna online
  - [ ] Millor presentació amb un punt roig o verd depenen estat
 
BACKEND:
- [X] Crear Middleware
- [X] Utilitza cache per indicar activitat usuari
- [X] Adaptar tests user
- [ ] Comprovar també loca activitat tipus API
- [X] Crear api que permeti obtenir la llista usuaris online
  - [X] Crear test
  - [X] Usuaris no logats no poden accedir
  - [X] No donar massa info sobre els usuaris online -> mapSimple
  
  
# Seeds 

- [ ] Crear un seed per tal de començar desde zero

# BUGS
- [ ] Alineament de la icona prepend de camp telèfon mòbil no surt ben alineada quan estem introduint mòbil

# CREDENTIALS JAVASCRIPT API/ MOBILE

PWA:
- [ ] Facilitar que l'usuari pugui guardar les seves credencials a eina de sistema operatiu Android
- https://whatwebcando.today/credentials.html

# SHARE

- [ ] Compartir incidències
- [X] Només mostrar el botó de compartir en entorns que ho suportin
  - [X] Pàgina de Landing
- [X] Compartir Landing Page
- https://whatwebcando.today/app-communication.html

# ROUTEROS

- [X] Paquet Laravel descartat no sembla que funcioni
- [X] Comprovat puc connectar però només sense encriptar:
  - [X] En local ok no problem no SSL
  - [] Explotació caldrà posar un tunel SSH 
- [ ] RouterOS API PROTOCOL: https://wiki.mikrotik.com/wiki/Manual:API
- [ ] Integració amb aplicació inventari
- [X] Client API PHP
  - https://github.com/pear2/Net_RouterOS
- [X] DESCARTAT Laravel: https://github.com/jjsquady/mikrotikapi  
  - [X] Docs: https://mum.mikrotik.com//presentations/NZ15/perkins.pdf

Instal·la com a dependència Paquet (https://packagist.org/packages/pear2/net_routeros):

```
composer require pear2/net_routeros
```

## DHCP

- [X] Fitxer web.php hi ha exemples directes de com funciona la API.
- [ ] https://github.com/pear2/Net_RouterOS/wiki/Util-basics
- [ ] Configuració bàsica de DHCP pot ser fixe
- [ ] Facilitar la gestió d'assignació estàtica de IP per adreça MAC
- [ ] Podria haver un apartat a l'aplicació que permetes comprovar si el PC desde el que ens connectem
té assignació estàtica IP (hi ha opció que Javascript obtingui MAC?) i sinó la té els admins poder afegir l'assignació
- [ ] Integrat amb l'inventari de PCS
Operacions interessants API:
- [ ] STATIC DHCP LEASES
  - [ ] Mostrar totes les entrades STATIC DHCP LEASES existents
  - [ ] Afegir entrada STATIC DHCP LEASE
  - [ ] Eliminar entrada STATIC DHCP LEASE
  - [ ] Modificar entrada STATIC DHCP LEASE
- [ ] Mostrar llista de totes les Leases tant estàtiques com dinàmiques

## DNS

- [ ] Simplificar les DNS de centre

# EBANDO

- 71.39 €/año (Impuestos incluidos)
- https://gestor.ebando.co/details

BANDOS/NOTIFICACIONES
- Borrador/Diferido/Publicar
- Etiquetas de bandos
- Titulo
- Mensaje (eina miniword)
- Adjuntos

- Crear notificacion
- Vista Prèvia 

# Backups

## Base de dades

- [ ] Backups de la base de dades a un compte de Google Drive
- [ ] Dos tipus
  - [ ] Accés ja configurat existent que ja utilitzem per altres coses
  - [ ] Aconseguir permisos via OAuth per indicar un altre compte
- [ ] https://medium.com/@dennissmink/laravel-backup-database-to-your-google-drive-f4728a2b74bd
- [ ] spatie/laravel-backup
- [ ] Còpies periodiques

### Fitxers

- [ ] Mantenir sincronitzada semper totes les operacions en fitxers per tal de guardar-los també a 
  Google Drive
- [ ] spatie/laravel-backup permet fitxers extres
  - https://docs.spatie.be/laravel-backup/v4/advanced-usage/adding-extra-files-to-a-backup 

# FOTOS AND AVATARS

- [ ] hi ha fotos i avatars
- [ ] Cal organitzar les dos coses i quan utilitzar un o altre
- [ ] no hi ha cap forma modificar photos
- [ ] Cal afegir Funcionalitat a profile



# COLORS

- [ ] Crear fitxer colors amb els colors del projecte
 - [ ] Posar un nom a cada color
 - [ ] utilitzar aquest colors a app.js  a tot arreu on utilitzem aquest colors

# TROUBLESHOOTING

- [ ] No va la creació d'usuaris, dona error i no crea l'usuari de Moodle (es queda pendent rebre resultat usuari moodle)
 - [ ] Hi ha una petició a l'api: https://iesebre.scool.test/api/v1/users/email/sdaasdnoexisteix@gmail.com que torna 404
  - [ ] no li dona temps
- [ ] No funcionen els filtres de professors a Teachers Management. Hi ha un munt de filtres per crear també
- [X] sense cuas va correctament! Per tant és un problema del worker
- [ ] NO van notificacions quan es fan per workers/queues. No troba classe 'App\Models\User'. Error:

Symfony\Component\Debug\Exception\FatalThrowableError: Class 'App\Models\User' not found in /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php:84
Stack trace:

#co0 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(55): Illuminate\Notifications\SendQueuedNotifications->restoreModel(Object(Illuminate\Contracts\Database\ModelIdentifier))
#1 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/SerializesModels.php(45): Illuminate\Notifications\SendQueuedNotifications->getRestoredPropertyValue(Object(Illuminate\Contracts\Database\ModelIdentifier))
#2 [internal function]: Illuminate\Notifications\SendQueuedNotifications->__wakeup()
#3 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(42): unserialize('O:48:"Illuminat...')
#4 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\Queue\CallQueuedHandler->call(Object(Illuminate\Queue\Jobs\RedisJob), Array)
#5 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\Queue\Jobs\Job->fire()
#6 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\Queue\Worker->process('redis', Object(Illuminate\Queue\Jobs\RedisJob), Object(Illuminate\Queue\WorkerOptions))
#7 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\Queue\Worker->runJob(Object(Illuminate\Queue\Jobs\RedisJob), 'redis', Object(Illuminate\Queue\WorkerOptions))
#8 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\Queue\Worker->daemon('redis', 'default', Object(Illuminate\Queue\WorkerOptions))
#9 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\Queue\Console\WorkCommand->runWorker('redis', 'default')
#10 /home/sergi/Code/acacha/tasks/vendor/laravel/horizon/src/Console/WorkCommand.php(46): Illuminate\Queue\Console\WorkCommand->handle()
#11 [internal function]: Laravel\Horizon\Console\WorkCommand->handle()
#12 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#13 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#14 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#15 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#16 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\Container\Container->call(Array)
#17 /home/sergi/Code/acacha/tasks/vendor/symfony/console/Command/Command.php(255): Illuminate\Console\Command->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#18 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#19 /home/sergi/Code/acacha/tasks/vendor/symfony/console/Application.php(886): Illuminate\Console\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#20 /home/sergi/Code/acacha/tasks/vendor/symfony/console/Application.php(262): Symfony\Component\Console\Application->doRunCommand(Object(Laravel\Horizon\Console\WorkCommand), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#21 /home/sergi/Code/acacha/tasks/vendor/symfony/console/Application.php(145): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#22 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\Component\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#23 /home/sergi/Code/acacha/tasks/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#24 /home/sergi/Code/acacha/tasks/artisan(37): Illuminate\Foundation\Console\Kernel->handle(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#25 {main}

- [X] NO VAN LOS LOGS de INCIDENCIES dona una BroadcastException. Solucionat era error no havia SSL ok a Valet i laravel sockets no anava bé.
  - [X] Potser no és problema del Log és problema del sistema Broadcast -> confirmat la entrada de log es crea però falla algo de broadcast
  - [X] event(new LogCreated($log)); de LogObserver és la línia que no va

{
    "message": "",
    "exception": "Illuminate\\Broadcasting\\BroadcastException",
    "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Broadcasting/Broadcasters/PusherBroadcaster.php",
    "line": 117,
    "trace": [
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Broadcasting/BroadcastEvent.php",
            "line": 48,
            "function": "broadcast",
            "class": "Illuminate\\Broadcasting\\Broadcasters\\PusherBroadcaster",
            "type": "->"
        },
        {
            "function": "handle",
            "class": "Illuminate\\Broadcasting\\BroadcastEvent",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php",
            "line": 29,
            "function": "call_user_func_array"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php",
            "line": 87,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php",
            "line": 31,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/Container.php",
            "line": 572,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 94,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 116,
            "function": "Illuminate\\Bus\\{closure}",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 98,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php",
            "line": 49,
            "function": "dispatchNow",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php",
            "line": 83,
            "function": "call",
            "class": "Illuminate\\Queue\\CallQueuedHandler",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/SyncQueue.php",
            "line": 42,
            "function": "fire",
            "class": "Illuminate\\Queue\\Jobs\\Job",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Queue.php",
            "line": 44,
            "function": "push",
            "class": "Illuminate\\Queue\\SyncQueue",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Broadcasting/BroadcastManager.php",
            "line": 128,
            "function": "pushOn",
            "class": "Illuminate\\Queue\\Queue",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Events/Dispatcher.php",
            "line": 280,
            "function": "queue",
            "class": "Illuminate\\Broadcasting\\BroadcastManager",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Events/Dispatcher.php",
            "line": 203,
            "function": "broadcastEvent",
            "class": "Illuminate\\Events\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php",
            "line": 482,
            "function": "dispatch",
            "class": "Illuminate\\Events\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Observers/LogObserver.php",
            "line": 25,
            "function": "event"
        },
        {
            "function": "created",
            "class": "App\\Observers\\LogObserver",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Events/Dispatcher.php",
            "line": 379,
            "function": "call_user_func_array"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Events/Dispatcher.php",
            "line": 209,
            "function": "Illuminate\\Events\\{closure}",
            "class": "Illuminate\\Events\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasEvents.php",
            "line": 162,
            "function": "dispatch",
            "class": "Illuminate\\Events\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php",
            "line": 809,
            "function": "fireModelEvent",
            "class": "Illuminate\\Database\\Eloquent\\Model",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php",
            "line": 651,
            "function": "performInsert",
            "class": "Illuminate\\Database\\Eloquent\\Model",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php",
            "line": 790,
            "function": "save",
            "class": "Illuminate\\Database\\Eloquent\\Model",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Support/helpers.php",
            "line": 1027,
            "function": "Illuminate\\Database\\Eloquent\\{closure}",
            "class": "Illuminate\\Database\\Eloquent\\Builder",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php",
            "line": 791,
            "function": "tap"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Support/Traits/ForwardsCalls.php",
            "line": 23,
            "function": "create",
            "class": "Illuminate\\Database\\Eloquent\\Builder",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php",
            "line": 1608,
            "function": "forwardCallTo",
            "class": "Illuminate\\Database\\Eloquent\\Model",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php",
            "line": 1620,
            "function": "__call",
            "class": "Illuminate\\Database\\Eloquent\\Model",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Logger/Incidents/IncidentLogger.php",
            "line": 197,
            "function": "__callStatic",
            "class": "Illuminate\\Database\\Eloquent\\Model",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Jobs/LogIncidentEvent.php",
            "line": 43,
            "function": "replyAdded",
            "class": "App\\Logger\\Incidents\\IncidentLogger",
            "type": "::"
        },
        {
            "function": "handle",
            "class": "App\\Jobs\\LogIncidentEvent",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php",
            "line": 29,
            "function": "call_user_func_array"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php",
            "line": 87,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php",
            "line": 31,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/Container.php",
            "line": 572,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 94,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 116,
            "function": "Illuminate\\Bus\\{closure}",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 98,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php",
            "line": 49,
            "function": "dispatchNow",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php",
            "line": 83,
            "function": "call",
            "class": "Illuminate\\Queue\\CallQueuedHandler",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/SyncQueue.php",
            "line": 42,
            "function": "fire",
            "class": "Illuminate\\Queue\\Jobs\\Job",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Queue.php",
            "line": 44,
            "function": "push",
            "class": "Illuminate\\Queue\\SyncQueue",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 177,
            "function": "pushOn",
            "class": "Illuminate\\Queue\\Queue",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 160,
            "function": "pushCommandToQueue",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php",
            "line": 73,
            "function": "dispatchToQueue",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Bus/PendingDispatch.php",
            "line": 112,
            "function": "dispatch",
            "class": "Illuminate\\Bus\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Listeners/Incidents/LogIncidentReplyAdded.php",
            "line": 33,
            "function": "__destruct",
            "class": "Illuminate\\Foundation\\Bus\\PendingDispatch",
            "type": "->"
        },
        {
            "function": "handle",
            "class": "App\\Listeners\\Incidents\\LogIncidentReplyAdded",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Events/Dispatcher.php",
            "line": 379,
            "function": "call_user_func_array"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Events/Dispatcher.php",
            "line": 209,
            "function": "Illuminate\\Events\\{closure}",
            "class": "Illuminate\\Events\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php",
            "line": 482,
            "function": "dispatch",
            "class": "Illuminate\\Events\\Dispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Http/Controllers/Tenant/Api/Incidents/IncidentRepliesController.php",
            "line": 50,
            "function": "event"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php",
            "line": 48,
            "function": "store",
            "class": "App\\Http\\Controllers\\Tenant\\Api\\Incidents\\IncidentRepliesController",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Route.php",
            "line": 212,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Route.php",
            "line": 169,
            "function": "runController",
            "class": "Illuminate\\Routing\\Route",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
            "line": 682,
            "function": "run",
            "class": "Illuminate\\Routing\\Route",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php",
            "line": 43,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Auth\\Middleware\\Authenticate",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php",
            "line": 41,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Http/Middleware/EnforceTenancy.php",
            "line": 42,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "App\\Http\\Middleware\\EnforceTenancy",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/app/Http/Middleware/Tenant.php",
            "line": 33,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "App\\Http\\Middleware\\Tenant",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php",
            "line": 58,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
            "line": 684,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
            "line": 659,
            "function": "runRouteWithinStack",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
            "line": 625,
            "function": "runRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
            "line": 614,
            "function": "dispatchToRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
            "line": 176,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Foundation\\Http\\{closure}",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/barryvdh/laravel-debugbar/src/Middleware/InjectDebugbar.php",
            "line": 65,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Barryvdh\\Debugbar\\Middleware\\InjectDebugbar",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/fideloper/proxy/src/TrustProxies.php",
            "line": 57,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Fideloper\\Proxy\\TrustProxies",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
            "line": 31,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
            "line": 31,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php",
            "line": 27,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/CheckForMaintenanceMode.php",
            "line": 62,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
            "line": 151,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
            "line": 116,
            "function": "sendRequestThroughRouter",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "/home/sergi/Code/acacha/scool/public/index.php",
            "line": 55,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "/home/sergi/.config/composer/vendor/cpriego/valet-linux/server.php",
            "line": 204,
            "function": "require"
        }
    ]
}

# BARRA NAVEGACIÓ LATERAL DRETA

- [ ] No mostrar data de creació o al menys no utilitzar Labels sinó calen

# USER PROFILE/ TEACHER PROFILE

- [ ] Mostrar informació de permisos
 - [ ] Rols que té l'usuari assignat
 - [ ] permissions-card 
- [ ] El link al teacher profile no VA!!
- [X] Error 403 amb usuaris normals no poden veure el seu propi perfil https://iesebre.scool.test/users/6
- [ ] Un cop tancat el perfil es mostri la pàgina Home
- [ ] Mantenir-se a la mateixqa pàgina a la que s'estava al fer click a "més informació" per tal de veure el pèrfil
- [X] CARD DE DADES D'USUARI
- [X] CARD de DADES PERSONALS
Sí és professor:
- [ ] Dades professionals de professor

# DADES PERSONALS

## PERSONAL DATA CARD

Amb labels (tooltips) o alguna forma identificar que és que:
- [X] Nom
- [X] Cognom1
- [X] Cognom2
Sense labels
- [X] Identificador amb text petit tipus
  - [X] Tipus identificador
- [X] Home/Dona
- [X] Estat civil
- [X] Telèfon
- [ ] Altres Telèfons
  - [ ] Icona info que mostri la resta de telefons al fer click o al fer hover
- [X] Mòbil 
 - [ ] Altres mòbils
- [X] Email
  - [ ] Altres emails
- [X] Notes

Naixement:
- [X] Text: Nàscut el DATA_NAIXEMENT_FORMATADA a LLOC_NAIXEMENT amb tooltip POSTAL CODE

Adreçes:
- [ ] On mostrar l'adreça i en quin format


## LOPD. Demanar esborrar dades locals

- [ ] Formulari públic de petició d'esborrat de dades personals
  - [ ] En tots els casos sembla que es demana identificació -> Còpia DNI
  - [ ] Pot ser un document per imprimir 
- [ ] Formulari un cop logat
  - [ ] Poder eliminar les dades personals però mantenir registre de l'usuari -> personal_data
  - [ ] Email personal que fer?
  - [ ] Nom d'usuari que fer
  - [ ] Del saga o similars s'esborren les dades?
- [ ] Deixar de rebre notificacions: això pot ser una configuració general  

## LINK AMB ALTRES MODELS

### Usuaris
- [ ] Ara hi ha un link d'usuaris a personal data (és un botó accíó que fa de link)
  - [ ] Si l'usuari no té dades personals que el link ho digui (posar un badge més)
   -  [ ] Al fer click si no hi ha dades personals s'obre el formulari per afegir dades personals 
   amb l'associació amb l'usuari ja feta!
   
## ACCIONS
- [ ] Pendent de fer les accions

## VISTA DATATABLES
- [ ] Limitar ample camps
  - [ ] Usuari local
- [ ] Formatar dates   
- [ ] Camp usuari
  - [ ] Podriem mostrar la foto/avatar
- [ ] Operacions inline:
  - [ ] Nom, cognom i 2n cognom s'han de modificar en bloc i sempre sincronitzat amb username
  
## FILTRES
- Filtres
 - [ ] Amb usuari associat
 - [ ] Sense usuari associat
 - [ ] Amb identificador
 - [ ] Sense identificador personal
 - [ ] Sense email
 - [ ] Amb email
 - [ ] Sense mobile
 - [ ] Amb mobile
 - [ ] Per sexe  
 
## API:
- [X] Operació refresh de les dades personals 
  - [X] Crear test i API, assegurar-se dades sincornitzades amb vista web
Operacions massives:
- [ ] DELETE
  - [X] Interfície gràdica preparada
  - [ ] Falta API             
             
# NOTIFICATIONS

- [X] Usuaris normals no poden veure mòdul notifications (error 403)
- [X] Usuaris normals no han de veure la llista de TOTES LES NOTIFICACIONS, només les seves

QUE CAL PER SUBSTITUIR E-BANDO/O SIMILAR:
- [ ] Apartat per enviar notificacions generals
  - Formulari:
    - [ ] Escollir usuari/s
    - [ ] Tipus de Notificació. De moment només una general
    - [ ] Text de la notificació
    - [ ] Altres camps per a les notificacions:
      - [ ] icons
      - [ ] Vibrar

ENVIAR NOTIFICACIÓ
- [ ] Camps extres com description, icon i similars
 
USER NOTIFICATIONS
- [ ] Toolbar user notifications: switch llegides/no llegides
  - [ ] Filtre per típus
- [ ] Vistes show centrades en la presentació sense labels ni datatables
- [ ] Cal definir un format comú de data per a les notificacions. Que pot tenir data:
  - [ ] title
  - [ ] description
  - [ ] icon
  - [ ] vibrate sí/no
  - [ ] Altres

TODO
- [ ] Card per a enviar notificacions
  - [ ] Posar toolbar
  - [X] Formulari per enviar notificació simple a un usuari
  - [ ] Múltiples usuaris de cop
- [ ] Widget, implementar temps real
  - [ ] Rebi push notification amb avís quan hi ha una notificació nova
  


INCIDÈNCIES i NOTIFICACIONS
- [ ] Quan s'afegeix un comentari a una incidència que es propia rebre notificació
  - [ ] NOMÉS ENVIAR NOTIFICACIÓ SI EL COMENTARI LA AFEGIT UNA ALTRE USUARI! 
  - [ ] Notificació tindrà un text tipus: 'Sha afegit un comentari a una incidència'
  - [ ] Al fer click: marcar com a llegida i
  - [ ] Mostrar la incidència
  - [ ] Extres: Icona associada a la notificació?
- [ ] Quan es tanca una incidència que es propia rebre notificació:
    - [ ] Notificació tindrà un text tipus: S'ha tancat una incidència'
    - [ ] Al fer click: marcar com a llegida i
    - [ ] Mostrar la incidència
    - [ ] Extres: Icona associada a la notificació?    
- [ ] Quan ens assignen una incidència:
    - [ ] Notificació tindrà un text tipus: Se us ha assignat la incidència'
    - [ ] Al fer click: marcar com a llegida i
    - [ ] Mostrar la incidència
    - [ ] Extres: Icona associada a la notificació?    
- [ ] Quan assignen una incidència que es nostra
    - [ ] Notificació tindrà un text tipus: Se us ha assignat la incidència'
    - [ ] Al fer click: marcar com a llegida i
    - [ ] Mostrar la incidència
    - [ ] Extres: Icona associada a la notificació?        
  
Recursos
- https://laravel.com/docs/5.7/notifications

# INBOUND EMAIL | Correu electrònics Inbound

MAILGUN:
- https://documentation.mailgun.com/en/latest/quickstart-receiving.html#add-receiving-mx-records
- https://www.mailgun.com/inbound-routing
- https://docs.beyondco.de/laravel-mailbox/1.0/drivers/drivers.html#sendgrid

Idea:
- [ ] Permetre crear incidències enviant un email
- [ ] Només els usuaris registrats i amb permisos (Incidents rol i email coincideixi amb el d'un usuari) podran enviar emails
- [ ] Un cop enviat email es rep un email de confirmació, fins que no es confirma no es crea la incidència
- [ ] Subject serà el del email i la descripció el contingut

## Configuració DNS del domini

Registres MX:

Type	Value	Purpose
MX	mxa.mailgun.org	Receiving (Optional)
MX	mxb.mailgun.org	Receiving (Optional)

IMPORTANT: iesebre.com -> tot s'envia a GMAIL/GSUITE

Cal fer-ho per a un subdomini, a més es pot fer a scool.cat

- iesebre.scool.cat -> incidencies@iesebre.scool.cat
- DNS de Digital Ocean: Afegir registre MX per enviar emails a Mailgun
- MAILBOX_MAILGUN_KEY= api key de mailgun https://docs.beyondco.de/laravel-mailbox/1.0/drivers/drivers.html#mailgun
- RUTA CATCH ALL -> https://awesome-laravel.com/laravel-mailbox/mailgun/mime.
  - Interessant també enviar copia a incidencies@iesebre.com


# TODOS finals abans posar explotació

- [ ] Recordar executar de tant en tant abans de passar a producció. S'executa npm run production (abans atureu npm run hot) a local abans de fer
un merge amb producció i pujar els canvis. 
- [ ] Estan instal·lats com a CDN externs. Instal·lació local: https://vuetifyjs.com/en/getting-started/quick-start
- [ ] A més vuetify està agafat de unpkg que a vegades falla
- [ ] També moure fonts de Icones de Material i de Font awesome 
- [ ] Some continuos integration
- [ ] Activar a explotació les cuas
  - [ ] Activar alguna eina per saber com funcionen les cues i estar alerta possibles errors
  - [ ] https://laravel.com/docs/5.7/horizon
- [ ] Treure botó de Login amb Facebook i Register amb Facebook
- [ ] Config per poder desactivar el registre amb missatge que indiqui el pq està desactivat
- [ ] Home temporal (desactivar la que hi ha ara que és un exemple concepte)
 - [ ] Home Superadmin: deixar el que hi ha ara
 - [ ] Home Profes:
    - [ ] Mostrar missatge de confirmar email si encara no l'ha confirmat! 
    - [ ] Incidències pendents de l'usuari?
    - [ ] Notícies: Aplicació incidències
    - [ ] Mostrar emails enviats a l'usuari? (Telescope ho fa fer algo similar)
    - [ ] Mostrar registre de canvis de l'usuari
- [ ] Icona/logo del centre
- [ ] Colors del centre
- [ ] Avatar s'enva fora de la Toolbar
- [ ] No mostrar icona (campana) notificacions sinó funciona o no hi ha
- [ ] FONT: Alguns titols surten tallats alguns caràcters com la g o la p (per la part baixa)
- [ ] Welcome Page: adaptar al centre
- [ ] DOCS Incidències: https://docs.scool.cat/docs/1.0/incidents
- [ ] Acabar o no mostrar TODO estadístiques
- [ ] Acabar o no mostrar TODO exportar a Excel
- [ ] No mostrar Teacher profile o Acabar-lo
- [ ] Boto de sortir al costat de l'Avatar que no calgui entrar?
- [ ] Canviar email ara es pot fer OK -> però hauria de posar el email a no confirmat

# Operacions INLINE/SINGULARS

## Tipus usuari:

- [X] Crear Component per editar el tipus usuari inline a partir d'una icona
- [ ] Implicacions canviar tipus usuari
  - [ ] Canviar directament sense tenir res més en compte -> Només si es té clar el que es fa
  - [ ] Relació amb els checks pendents
    - [ ] Check per ser professor:
      - [ ] Cal tenir usuari
      - [ ] Cal tenir un teacher_id vinculi amb la taula teachers

## Mòbil usuari

- [ ] Implicacions canviar mòbil
  - [ ] Falta sistema confirmació/verificació mòbils via SMS
  - [ ] Un cop es canvia un mòbil s'ha de tornar a posar verificat a false
  - [ ] Acció enviar SMS per validar mòbil

# FITXA USUARI/ PROFILE

USER CARD:
Dos users cards: Mostrar dades i edit
EDIT:
- Full screen dialog?
- Poder modificar totes les dades
- [ ] Amb Labels i camps de formulari però que siguin els labels secundaris -> Important les dades
- [ ] Id no modificable
- [ ] Modificar email inline aprofitar ja tenim com fer-ho a la llista (modificació només email)
- [ ] Photo Modificacle amb doble click -> Photo Component
- [ ] Nom modificable inline i que sincronitzi amb givenName, Sn1 i Sn2
- [ ] Tipus usuari modificable inline


SHOW

- [X] Crear una user card per mostrar informació NOMÉS usuari (taula user sense cap taula extra suport com person o Google, Moodle, etc)
  - [ ] Photo (photo)
     - [ ] Ara utilitzem Avatar no photos
      - No confondre photo i Avatar (la photo és la associada a l'usuari)
      - Tooltip mostri nom complet i email
- [ ] Redisseny de l'estil
 - [ ] Potser cal fer sempre dos vistes una de només consultar i l'altre edició
 - [ ] Cal parcelar el disseny per parts, no tothom tindrà totes les parts
 - [ ] Depenent dels permisos de l'usuari es podran fer més o menys coses
- [X] URL directa d'accés ('/users/1')
- [X] Usuaris poden veure la seva fitxa ('/user/profile')

PERMISOS:
- [ ] Admins poden veure fitxes/perfils de tothom i users managers també
- [ ] Test usuaris normals no poden veure fitxa completa usuari
  - [ ] Fitxa pública:
  - [ ] Noms i cognoms
  - [ ] Email corporatiu -> email personal no 
  - [ ] Avatar|Photo
  - [ ] Tipus usuari

## Card Comptes externes

- Mostrar de comptes externes d'un usuari:
- [ ] Disseny similar UserCard 
- [ ] Moodle
      - [ ] Moodle id, idnumber, nom usuari i resta de dades Moodle
      - [ ] Accés/link a gestió usuaris Moodle
      - [ ] Accés/link del usuari a Moodle 
- [ ] Gsuite/Google User
      - [ ] Email corporatiu
      - [ ] Google Id  
      - [ ] Accés/link del usuari a Google
- [ ] Ldap

     
     
     
# LANDING PAGE

Millores interfície
- Formulari de registre no cal fullscreen en pantalla completa
- CTA: Incloure un camp email per recollir el email as soon as possible

Seccions:
- Per a tota la comunitat:
  - Usuaris: Tres principals i un Altres agrupi la resta
- Curriculum
  - Accés a les pàgines públiques amb el currículum del centre  
- Creat utilitzant l'eina scool  
- Formulari de contacte
  - Envia email a email predefinit  

Footer:
- Copyright
- FAQ
- Contacte
- Legal

# Mòdul de tasques

- [ ] Moure Aplicació tasques a scool
  - [ ] Seria un bon complement per a tenir una llista de tasques pendents per projectes
  - [ ] Obrir un projecte per maninfo 

# USERS MOBILE (Mòbil dels usuaris)

- [ ] Recollida del mòbil
  - [ ] No es poden tenir dos usuaris amb el mateix mòbil
  - [ ] Al canviar el mòbil s'ha de posar el nou mòbil com a no confirmat
  - [ ] Poder esborrar telefòn 
  - [X] Widget amb un boto + per afegir mòbil a un usuari 
  - [ ] Només mòbils locals (en el nostre cas codi país +34 davant)
  - [ ] Configurable el codi de pais
  - [X] Els números de mòbils s'agafen sense codi país i se presuposa el país segons configuració
  - [ ] Guardar tots els telefons a base de dades sense 34 davant (no cal +)
  - [X] Vuetify input file masks
- [ ] Confirmar mòbil -> enviar SMS
  - [ ] Mobile confirmation by entering a code send my SMS
  - [ ] Codis tenen un periode màxim de vàlidesa 
  - [ ] Laravel notifications: Nexmo
   - https://laravel.com/docs/5.7/notifications#sms-notifications
  - [ ] stur@iesebre.com és l'usuari utilitzo amb el meu mòbil personal
  - Crèdit de 2e per fer proves i paareix text
- [ ] Shorten links:
  - [ ] https://zapier.com/blog/best-url-shorteners/
  - [ ] Hi ha serveis però una opció es instal·lar un de propi tipus https://scool.cat/r/HASH
  - [ ] POlr: https://polrproject.org/?utm_source=zapier.com&utm_medium=referral&utm_campaign=zapier
     - [ ] Utilitza Larave Lumen 
     - [ ] Fer quelcom similar. redireccions Laravel per convertir en URLS curtes URLS llarges

# MÒDUL DE COMANDES

- [ ] Associat a incidències (incidències que queden pendents d'una comanda)
- [ ] Comanda oberta/tancada
- [ ] Rebre un email cada cop hi ha una nova comanda
- [ ] Cal validació de comandes abans enviar administradora?

# GOOGLE USERS MANAGEMENT

- FILTRES:
 - [ ] Amb Avatar
 - [ ] Sense Avatar
 - [ ] Sense usuari local NO funciona bé?
OPERACIONS MASSIVES:
- [ ] Delete
  - [X] Interfície gràfica preparada
  - [ ] Falta API
 
- [ ] Crear usuari de Google
  - [ ] Si s'escull no crear usuari de Moodle o de Google o Ldap aleshores al següent pas no mostrar el progress intentant obtenir les dades de Moodle o de Google
- [ ] Fer el mateix que s'ha fet amb els usuaris de Moodle:
  - [ ] Modificar la llista d'usuaris de Google per afegir camps relacionats amb l'usuari local
    - [ ] IMPORTANT (IGUAL PASSARA A MOODLE) -> Estigui sincronitzada la llista usuaris per web i per API!!!
    - [ ] Faltaria comprovar hi ha el registre local taula relaciona usuaris locals amb usuaris Moodle/Google
    - [ ] localUser -> Usuari local associat
    - [ ] inSync -> Indiqui estan sincronitzats correctament
    - [ ] errorMessages -> Textos amb errors de sincronització
    - [ ] flags -> marques d'estat per facilitar accions
    - [ ] La clau de relació principal: utilitzar un camp de Google per identificar -> employeeId 
    - [ ] Altres claus:
      - [ ] personalEmail -> email usuari 
      - [ ] primaryEmail -> corporativeEmail
      - [ ] Tema sincronitzar noms igual que Moodle operació sync   
- [ ] Poder accedir al show d'un usuari amb link directe
  - [ ] Per Id
  - [ ] Per email corporatiu
- [ ] Esborrar usuaris de Google
  - [ ] Quan té compte local associada
  - [ ] Mostrar warning té compte local associada. Si desitja tirar endavant s'eliminara l'associació

# SERVICE WORKERS

- [X] Afecten npm run hot?: https://medium.com/@sergiturbadenas/how-to-solve-problems-using-hmr-hot-module-replacement-with-laravel-mix-or-webpack-dev-server-fe6c34ae76b6

# PEOPLE MANAGEMENT. PERSONS MANAGEMENT

## Vista dades personals | INLINE MODIFICATIONS

Tots els camps són especials
- [ ] Botó edit per fer la modificació 
 
## Vista dades personals

- [ ] Mostrar notes
 - [X] Mostrar com a tooltip del email del usuari
 - [ ] Posar una acció per veure les notes (i de pas que les permeti editar)
- [X] Mostrar taula en mode compacte
- [X] Mostrar Status civil
- [ ] Mostrar lloc de naixement tenin en compte birthlocation (si birthplace_id és null utilitzar birthlocation)
- [X] Mostrar identificador (el valor no el ID)
  - [X] Mostrar el tipus identificador
  - [X] Mostrar el id del identificador amb v-tooltip
  - [] Mostrar altres identificadors
- [X] Mostrar usuari associat a les dades personals
  - [X] Mostrar el avatar (o en el seu defecte la imatge per defecte)
  - [X] Mostrar també email del usuari
- [X] Mostrar altres mòbils
- [X] Mostrar altres emails
- [X] Mostrar altres telèfons
- [ ] Mostrar l'usuari corporatiu?
 - [ ] Sinó té usuari corporatiu nostrar cap

# Users management. Gestió d'usuaris

## USER ADD WIZARD

### USUARI LDAP
- [ ] TOT PER FER

## LLISTA DATATABLES

- [ ] Usuari online
- [ ] Columna ldap
- [ ] Poder fer que un usuari sigui admin:
 - [ ] només els superadmins poden afegir altres admins
 - [ ] Certs superadmins (fitxer de config) no es poden eliminar
- [ ] Poder buscar usuaris:
  - [ ] Nom
  - [ ] Identificador
- [ ] Llista d'usuaris -> mostrar columna online de l'usuari
- [ ] Acció -> Enviar notificació a l'usuari
- [ ] Link amb dades personals
  - [ ] Sinó hi ha dades personals no és un link és un diàleg flotant que mostrar el formulari 3 (el de dades personals)
    - [ ] Petita modificació al formulari tres -> incloure Sn1, Sn2 i Givenname

## SUBFOMULARI AFEGIR USUARI

- [ ] Component per cercar usuaris ja existents
  - [ ] Cercar per identificadors. També per noms. Fuzzy search? mostrar casos no coincidents però molt semblants
  - [ ] Laravel Scout? Algolia? o manual
  - [ ] Ha d'evitar donar d'alta varios cops el mateix usuari. Com integrar
  - [ ] uuid com a id d'usuaris? podria facilitar migracions i treball amb múltiples taules
- [ ] Tema telefòn mòbil i enviar confirmació SMS (Nexmo)

## SUBFOMULARI CANVIAR AVATAR

- [X] Esperant les dades de Google -> però un cop s'ha crear l'usuari no s'atura l'spinner
- [X] No funciona la creació usuari Moodle
 - [X] De fet si funciona (petició API crea l'usuari) però la interfície/frontend Javascript de Vue dona error
 - [X] Surt snackbar donant error: "Error de xarxa"
- [X] Boto seguent amb jerarquia primaria
- [X] NO mostrar progress bars MoodleUser, ldapUser i GoogleUser si s'ha indicat no crear aquests usuaris
  - [X] HA d'haver-hi comunicació entre el component del primer step i el del segon via el pare o Vuex?
- [ ] TODO. No es canvia avatar
  - [ ] Hi ha tot un tema pendent sobre photos i avatars

## FORMULARI DADES PERSONALS

- [X] Widget altres identificadors:
  - [X] Falta el formulari altres identificadors  
  - [X] Provar es guarden els altres identificadors al fer el submit
  
Submit:
- [X] Detecció de DNI duplicat
  - [X] Que sigui el propi camp DNI que comprovi al escriure un DNI que és un DNI ja existent!
  - [X] Ara el formulari dona un error de duplicate key al fer submit
  - [ ] Mostrar informació de l'usuari que té assignat l'identificador
- [ ] Evitar que es pugui crear un nou registre a la taula person amb tots els camps buits (menys user_id)
  - [ ] Validació Javascript
  - [X] Validació backend/PHP/Laravel
- [X] Mòbil de l'usuari i mòbil personal 
  - [ ] Omplir el camp mòbil amb el mòbil de l'usuari si s'ha proporcionat
- [X] Tancament de la finestra afegir usuari neteja camps personals
- [X] Comprovar l'adreça afegida té correcte el person_id. Test actualitzat
- [X] Comprovar el identifier_id és correcte
- [X] civil_status no va no es guarda a la base de dades
- [X] email -> NO EL PROPORCIONEM! Proporcionar el email del user!
- [X] otheremails comprovar
- [X] notes comprovar
- [X] other_phones i other_mobiles i other_emails
Altres
- [ ] Dates de naixement amb la màscara correcta però dates incorrectes -> Error a la consola
- [ ] Comprovar/implementar switch validació
- [X] Comprovar/implementar switch camps requerits
  - [X] Problema al passar d'estat requerit a no requerit. Alguns camps continuen sent requerits
- [X] Al esborrar tots els camps de birthplace no s'esborra birthplace (és un objecte amb postalcode buit i province i localiltat null)
- [X] Camps que no s'esborren al BUIDAR CAMPS
  - [X] Data de naixement
  - [X] Codi postal naixement
  - [X] Localitat de Naixement
  - [X] Província de naixement
  - [X] Estat cívil
  - [X] Adreça -street
  - [X] Adreça - number
  - [X] Adreça - Pis
  - [X] Adreça - # pis
  - [X] Codi postal adreça
  - [X] Localitat adreça
  - [X] Provincia adreça 

## Verificacions

### Verificació email

- [ ] Quan avisar a l'usuari que ha de validar el correu electrònic?
  - [ ] Al dashboard/Home amb un alert i un CTA clar centrar i el primer que apareix

## Passwords

IMPORTANT:
- [ ] Centralitzar les operacions de canvi de paraula de pas

Email password reset:
- [ ] Exemple URL: https://iesebre.scool.test/password/reset/d6350f7774c0d1be132260c1f5d9429c22c8d5bf0e9e7ad9a74a71fa49fc0fe8
  - [ ] Canviar el formulari per indicar quins password extres es volen sincronitzar?
  - [ ] Per defecte canviar tots els passwords?

Expiració de password
- [ ] Camp password_expires_at. Afegir a la migració com a nullable i datetime
- [ ] Afegir a fillable al Model User
- [ ] Crear una URL ('/password/expired') i una vista per mostrar els formulari de canvi de paraula de pas
  - [ ] Crear entrada API per fer canvi paraula de pas
- [ ] Al login -> Si el password està expirat mostrar formulari per canviar paraula de pas
- [ ] https://laraveldaily.com/password-expired-force-change-password-every-30-days/
- [ ] Middleware password_expired

Gestió de password:
- [ ] Acció de menú que permeti canviar paraula de pas de l'usuari:
 - [ ] Aprofitar per també utilitzar-la al perfil de l'usuari
 - [ ] Switchs si es vol sincronitzar paraules de pas A Ldap/Moodle/Google
 - [ ] Generador de password aleatori
 - [ ] Enviar email a l'usuari
 - [ ] Forçar canvi al pròxim Login
 
## CHANGELOG:
- [ ] Igual que incidències logar totes les accions 

## PENDENT

- [ ] Eliminar tots els links i valors hardcoded de Javascript apuntant a iesebre.com i agafar-los del tenant

EDIT
- [X] Modificar el tipus usuari
  - [ ] Questions a tenir en compte en la modificació
     - [ ] Era professor: avisar? o que fer si té assignada una plaça
     - [ ] Alumne: té matrícules?
     - [ ] Altres

USER DELETE:
- [ ] Preguntar si també es vol eliminar usuari de Google, usuari de Ldap i usuari de Moodle
- [ ] Proteccions contra errors: usuaris protegits no es puguin esborrar    

USER ADD WIZARD
- [ ] Tema password i sincronització dels diferents password
  - [ ] Moodle no permet passar hash del password -> S'ha de canviar password de Moodle en el moment es canvia password local
  - [ ] Com gestionar-lo?
  - [ ] Lligar amb el dialeg pendent que permetra canviar paraules de pas

- [X] Estils:
  - [X] Botons rojos no!
  - [X] Aplicar jerarquia! Quina botons són més important i quins secundaris

OPERACIONS MASSIVES:
- [X] Eliminar -> FET
- [ ] Check -> Comprovar incoherencies:
  - [ ] NO quadra tipus usuari i rol
    - [ ] Si ets teacher has de tenir el rol Teacher
    - [ ] Si ets alumne has de tenir el rol Student
  - [ ] Alumne sense email corporatiu
  - [ ] Teacher sense email corporatiu
  - [ ] Teacher sense avatar
  - [ ] Personal sense email corporatiu
  - [ ] Teacher/Alumne/Profe sense dades personals
  - [ ] Teacher/Alumne/Profe sense compte Google
  - [ ] Teacher/Alumne/Profe sense compte Moodle
  - [ ] Teacher/Alumne/Profe sense compte Ldap

OPERACIONS:
- [ ] Check individual d'un usuari
- [ ] Diàleg canviar paraula de pas
  - [ ] Poder posar una paraula de pas
  - [ ] Generador de paraules de pas i enviar per correu electrònic
  - [ ] Forçar canvi de paraula de pas al següent login
  
FILTRES
- [ ] Filtres usuaris
  - [X] Per Rol/s
    - [ ] No mostrar els usuaris admin al filtrar per Roles (tenen tots els rols i permisos)
  - [ ] Altres característiques/filtres:
     - [X] Mòbils no confirmats/verificats. TODO falta està funcionalitat
     - [ ] Sense avatar. TODO -> Falta crear camp (calculat) indiqui usuari no té avatar 
     - [ ] Logats desde un periode especific

ALTRES
- [ ] Mobile: de moment camp no obligatori però després podria servir com alternativa al email.
- [ ] Usuaris no tenen email poder utilitzar el mòbil i SMS per a fer autenticació?
- [X] Esborrat massiu d'usuaris
  - [ ] Protegir alguns usuaris -> no es puguin esborrar:
    - [ ] Superadmins
    - [ ] Configuració: altres usuaris no es puguin esborrar     

## RELACIONS AMB ALTRES ENTITATS/MODELS

### GOOGLE/GSUITE
- [ ] Al editar link a les dades de Google d'aquest usuari
 - [ ] POder accedir al show d'un usuari de Google directament via link
 
### PEOPLE/PERSON

  
  
  
  
# ROLS AND PERMISSIONS MANAGEMENT

ROLS:
- [ ] CRUD DE ROLS
  - [X] LIST/RETRIEVE
  - [ ] ADD
  - [ ] EDIT ONLINE
  - [ ] DELETE
    - [ ] Protegir certs rols-> No es puguin esborrar
  - [ ] Massive delete

PERMISSIONS:
- [ ] CRUD DE ROLS
- [ ] Protegir certs Rols -> NO ES PODEN ESBORRAR

# USUARIS ACABATS DE REGISTRAR | SENSE ROLS

## DASHBOARD

- [ ] Usuaris sense email verificat -> VERIFICAR EMAIL (enviar email verificació) 

- Quan usuari serà nou? No té assignat tipus d'usuari
- Text de benvinguda -> Gran i centrat (Benvingut)
- Emoticona de saludar ma movent-se: https://instapage.com/blog/how-to-setup-welcome-pages (exemple slack)
- Escollir tipus d'usuari que interesa ser (principals).
  - Alumne
  - Profesor
  - Personal
- Altres: secundari
  - Ex-alumne
  - Ex-professor
  - Altres
  - Familiars

### Exalumne

- Per aquells que els interessi mantenir contacte amb el centre
- Recuperar usuari si havien tingut? Com fer?
- Indicar les xarxes socials a les que es pot apuntar per estar al dia de les novetats del centre 

### Alumne

Si l'usuari vol ser alumne ha de formalitzar una matrícula:
- Periode de matrícula: cal fer un manteniment al mòdul matrícula que permeti indicar periode de matrícula
- Mantenir-me informat|Notificar/Avís quan s'obri el periode de matrícula/preinscripció
  - Indicar els estudis en els que el potencial alumne està interessat -> llista
  - Agafar alguna dada extra com el telèfon Mòbil -> Potser també l'adreça per fer mailing?
  - Indicar les xarxes socials a les que es pot apuntar per estar al dia de les novetats del centre 
- Sí es periode de matrícula i està activada la automatriculació pot fer la matrícua
- Preinscripció?

### Professor 
- Usuari potencial professor
### Ex-profesor
- Posibilitar mantenir compte de centre com correu

### Personal

### Superusuaris
- Usuaris admin amb tots els permisos


# POSITIONS MANAGER

Becaris:
- [ ] Omplir la taula de posicions amb tots els càrrecs
- [ ] A initialize_teachers assignar càrrecs a professors.

Positions table i Position Model

- LLista de càrrecs inclou Tutors, Tutors FCT, Caps de departament

Taula
- name
- shortname: ???
- Roles: rols associats al càrrec

- [ ] Dashboard -> pàgina principal usuari normal -> Comprovar si té algún rol assignat-> no? Que pugui demanar un rol
  - [X] Al assignar un càrrec que l'usuari rebi una notificació/email
  - [X] Acció reenviar email d'assignació càrrec
  - [ ] Al assignar un rol que l'usuari rebi una notificació/email
  - [ ] Lo mateix per reclamar un càrrec
  - [ ] Els usuaris configurats (settings) per a rebre notificacions de reclamacions de rols/càrrecs reben una notificació
  - [ ] Mostrar assignacions de rols pendents/aprovar -> Boto aprovar assignació  
- [ ] Dashboard -> pàgina usuari admin o positionsmanager
  - [ ] Mostrar càrrecs no deletable (imprescindibles com cap estudis) que no tenen cap profe assignat
  - [ ] Assignar usuaris a un càrrec
- Càrrecs:
  - [ ] No poder eliminar càrrecs deletables = false
  - [ ] Poden tenir o no rols assignats -> 1 càrrec -> n rols
    - [ ] assignRole to position
  - [ ] De moment fer les assignacions Càrreccs -> Rols hardcoded   
  - [X] Usuari/Usuaris que tenen un càrrec
    - [X] Assignar usuari a càrrec
    - [X] DeAssignar usuari a càrrec
    - [ ] Al assignar un usuari a un càrrec si el càrrec té rols assignar rols a l'usuari
    - [ ] Lo contrari de l'anterior.
  - [ ] Càrrecs/positions associats a algun recurs -> relació polimorfica:
    - [ ] Cap de departament -> Departament. Al crear un departament -> crear el càrrec
    - [ ] Departament grans tenen rol ajuda al cap de departament
    - [ ] Tutor -> Grup de classe. Al crear un grup de classe -> crear el càrrec
  
  
# Google Drive

- [ ] Curriculum
  - [ ] Crear una carpeta currículum que tingui tots els fitxers associats a curriculum:
  - [ ] Creada com a carpeta compartida (només cal el id -> setting the curriculum) amb tot els Institut o tohotm -> public
  - [ ] Ara està fet amb l'usuari sergitur@iesebre.com -> Canviar service account?
  - [ ] Utilizar sistema de fitxers de Laravel per guardar els fitxers a Google Drive -> estarà automàticament compartir com a nómés lectura a totho
  - [ ] Es podria fer públic absolutament a tothom -> ? Links públics a fitxers -> com obtenir? Tampoc cal pq es pot programar en PHP i major control
  - [ ] Estructura
    - [ ] Carpeta SCOOL
      - [ ] Carpeta Curriculum
        - [ ] Una carpeta per cada estudi -> Al crear un estudi -> crear la carpeta A més si s'han aportat fitxers guardar-los a la carpeta
        - [ ] Al eliminar Estudi -> canviar carpeta
        - [ ] Al modificar estudi (codi) -> modificar nom carpeta (serà el codi)
        - [ ] Operacions cares en temps -> queues 
        - [ ] Fitxers a guardar: imatge púplica apareix a l'estudi
        - [ ] Qualsevol altre fitxer públic com PDF a lleis, etc
        - [ ] Admin, cap estudis i caps de departament poden modificar els fitxers (a la app) i també es pot configurar al Drive
- [ ] No funciona teams drives ???

# Currículum wizard inicial configuració|settings:

- [ ] Preguntar el nombre d'estudis del centre
- [ ] Preguntar pels tipus d'estudis i crear les estiquetes d'estudis que pertoquen
  - [ ] Estudis LOE? EStudis LOGSE? quants
  - [ ] Estudis FP? quants?
    - [ ] Quants de CFGM?
    - [ ] Quants de CFGS
  - [ ] Estudis cursos accés? quants 
  - Taula settings

Al tenir la info d'estudis i estadístiques:
- [ ] Boto afegir estudis: Mostri un confirm per confirmar si realment es vol crear un nou estudi si ja estan tots donats d'alta.
En cas que es digui que si modificar el nombre màxim estudis
- [ ] Més validacions al afegir etiquetes: confirms tipus? Segur que voleu assignar la etiqueta CFGM, ja hi ha x CFGM donats d'alta?
  
Dashboard del cap d'estudis:
- [ ] Estat global del currículum: complet o pendent alguna cosa?
- [ ] Estadístiques nombre d'estudis per tipus i amb dades comparant la teòria i la realitat

# ALTA MPS I UFS:
- [ ] Wizard: un cop indicat el número de MPS -> Crei automàticament x MPS:
  - [ ] El codi omplert automàticament
  - [ ] El número UF omplert automàticament
  - [ ] El nom pot ser temporalment el codi -> TODO
  - [ ] Algún warning que indiqui hi ha alguna MP pendent posar nom correctament
- Camp calculat completat d'un estudi:
 - [ ] Hauria de comprovar més coses que nombre màxim UFS i MPS:
   - [ ] Hores de l'estudi === Suma hores MPS
   - [ ] Per cada MP -> Suma hores UFs === nombre hores MP
   - [ ] Té un departament assignat
   - [ ] Té una família assignada
   - [ ] No hi ha cap MP o cap UF amb noms temporals (valor per defecte de la UF al crear-la)
   
   
# Vista estudis
- [ ] Implementar expand a datatable al fer click a un estudi es mostra el pla docent?
- [ ] Cursos: número de cursos de l'estudi
  - [ ] Camp a la base de dades
  - [ ] Preguntar a l'afegir un estudi -> Preguntar el nombre de cursos -> Crear-los automàticament?
  - [ ] Mostrar a la llista
- [ ] Estat d'un estudi (camp CALCULAT, no afegir cap camp estat ni res similar a base de dades)
  - [X] Estat complet: estan donats d'alta tots els MPS indicats i totes les UFS
    - [ ] Tema cursos?
  - [X] Estat incomplet: falta algun MP o alguna UF
  - [ ] Subestats: 1 Falta algun MP 2 Falta alguna UF
    - [ ] Boto afegir MP. Només si falta alguna MP per afegir
       - [ ] Mostrar formulari afegir MPs però amb l'estudi ja escollit
    - [ ] Boto afegir UF. Només si falta alguna UF per afegir
       - [ ] Mostrar formulari afegir UFs però amb l'estudi ja escollit
       
# TODO CURRICULUM
- [ ] Tests unitaris
  - [ ] Mètode updateNumber i calculatenexNumber per UFs i per a MPS. Crear un test i refactoritzar
  - [ ] Codi wet repetit a UFS i MPS, fer un trait i reaprofitar
- [ ] Changelogs
 - [ ] A tots els models (studies, ufs, mps)
- [ ] subjectGroupTags: no hi ha component està insertat codi a SubjectAddForm -> crear component
 - [ ] Aprofitar component (codi WET) a la llista també no nomes al formulari de add
- [ ] Afegir hores setmanals a una UF, de fet té més sentit fer TOT per UFs que per MPS?
- [X] studiesTags: no hi ha component està insertat codi a SubjectAddForm -> crear component
 - [X] Aprofitar component (codi WET) a la llista també no nomes al formulari de add
- [ ] Permetre indicar etiquetes al afegir un studi (no ho pregunta el formulari)
- [ ] StudiesShow component:
  - [ ] Afegir Pla docent
  - [ ] Link a la vista pública (s'assemblen molt) i viceversa  
IDEA:
- Tot el tema dates (data inici i data fi, hores setmanals, etc), planificació de la UF té més sentit en fase d'horaris i/o desiderates (profes/caps departament) que no pas
al donar d'alta el currículum (cap estudis)
  - [ ] Ara mostro estes dades a currículum al donar d'alta però són opcionals en aquesta fase
  
Navegació:
- [ ] Mostrar botó currículum de l'estudi a les llistes UFS i MPS, per tal de veure la UF/MP en el seu context
 - [ ] Posat's que es desplaci fins la UF/MP i la marqui d'algún color es vegi remarcada  

Vistes públiques:
- [ ] Afegir botons de Login i Registre i navegació Welcome Page i Home
- [X] Mòdul/plugin Javascript de permissions funciona amb pàgines públiqes?
  - [X] Sí sempre i quan tingui la info de hader com window.user
  - [X] Evitar warning de console i/o errors window.user is null
- Vista general estudis per families
  - [ ] Filtrar estudis per etiquetes (CFGM, CFGS...)  
- Vista estudi
  - [ ] Navegador/Toolbar
    - [ ] Select estudis (canviar estudi)
    - [ ] Estudi seguent -> Estudi anterior << >>
  - Pla docent:
    - [ ] Opció de mostrar per cursos (en comptes d'ordenat per MPS, ordenat per cursos i MPs)
    - [ ] Opció mostrar dates (no falli si no estan posades)
      - [ ] Opció editar dates (només CurriculumManager o altres amb permis)
  - [ ] CurriculumManager:
    - [X] Mostrar icona edit al costat títol Mòduls Professionals -> Link a CRUD Mòduls Professionals
      - [ ] Posa'ts ja que vagi a la URL però activant el filtre de l'estudi que pertoca
    - [X] Mostrar icona edit al costat títol Unitats Formatives -> Link a CRUD Unitats Formatives
     - [ ] Posa'ts ja que vagi a la URL però activant el filtre de l'estudi que pertoca
    - [ ] Mostrar icona Add a MPS si falta algun MP (se sabrà pel nombre MPs de l'estudi i les que hi ha) al estudi per donar d'alta
      - [ ] Combinar amb una icona alerta al costat
    - [ ] Mostrar icona Add a UFS si falta alguna UF (se sabrà pel nombre UFS del MP i les que hi ha) al MP per donar d'alta
          - [ ] Combinar amb una icona alerta al costat    
    - [ ] Poder modificar nombre total UFS d'un estudi
    - [ ] Poder modificar hores
    - [ ] Poder modificar noms amb doble click (i/o icona edit)
      - [ ] Nom Estudi
      - [ ] Nom UF
      - [ ] Nom MP  
    - [ ] Poder modificar codis amb doble click (i/o icona edit)  
      - [ ] Codi Estudi
      - [ ] Codi MP
      - [ ] Codi UF
    - [ ] Poder modificar l'enllaç al tríptic i la programació
    - [ ] Cursos acadèmics: Link a l'edició dels cursos
    - [ ] Hores FCT
    - [ ] Dual sí/no
    - [ ] Dades de contacte   
 - [ ] Mostrar més info als usuaris Curriculum Manager
  - Vista general estudis per families
  - [ ] Warnings:
    - [X] Família/es sense estudis (null o zero)
  - Vista per a un estudi concret:
    - [ ] Warnings:
      - [X] No coincideix en número total hores MP amb la suma de hores de les UFS
      - [ ] No coincideix el número total de UFS del estudi amb el nombre UFS real
      - [ ] No coincideix en número total de MPs de l'estudi amb els MPS reals
      - [ ] Estudi sense projecte/sintesi
      - [ ] Estudi sense FCT
      - [ ] Estudi sense assignatures transversals (FOL, EIE, Àngles)
      
Shows:
 - [ ] Falten els shows de tots els mòdels
   - [ ] Indirectament un cop van el shows es pot fer anar la navegació (links per mostrar la info d'un model específic) 

Edits:
 - [ ] Quins casos cal? La majoria d'edits són parcials i es poden fer a altres vistes...

Selects:
- [ ] Afegir icona append per refrescar elements a un select:
  -  [ ] Tots els selects està pendent
- [ ] Afegir icona prepend per afegir element a un select:
  - [ ] Deparment Select
  - [ ] Family Select
- [ ] Als selects que tenen opció afegir un item al select (estudis select) fer que si s'afegeix un nou estudi passi a ser el seleccionat
  - [X] StudySelect
  - [ ] Altres selects
- [ ] Opció d'editar el seleccionat (per exemple editar estudi s'ha seleccionat)
 
Altres:
- [X] Eliminar Model Law -> Substituit per etiquetes d'estudis -> Comprovar tots els testos van igual ok
- [X] Tipus MP -> SubjectGroupTypes -> Són etiquetes es poden posar als MPS
  - [X] Mostrar Etiquetes als MPS a la visa
  - [X] Filtrar per etiquetes a la vista MPs
  - [X] Afegir/eliminar etiquetes a la vista MPs
  - [X] Afegir/eliminar etiquetes al form de create.

Curriculum settings:
- [ ] Data inici curs
- [ ] Data fi curs
- [ ] Dies festius/no hàbils

Afegir MPS
- [ ] Study acabat d'afegir al select, ok se selecciona bé però no es mostra/actualitza nombre MPS de l'estudi
- [X] Permetre definir/modificar nombre màxim de MPs d'un estudi seleccionat
- [ ] Llistat de MPS -> Links als MPS (show) al fer clic .Pendent de funcionar shows
- [X] Llistat de MPS -> Afegir icona remove al chip que permeti eliminar el MP (prèvia confirmació amb confirm)
- [ ] Format de les dates inici i fí
- [ ] Settings Currículum relacionades:
  - [ ] Data inici curs
  - [ ] Data fí curs
  - [ ] Nombre setmanes del curs: famoses "35"
  - [ ] Definir número de cada setmana i periodes de festes
- Vàlidacions
  - [ ] La data d'inici de la UF no pot ser inferior a la data d'ínici de curs
  - [ ] La data fí de la UF no pot ser superior a la data fí de curs
  - [ ] ???  
Rendiment:
- [X] Solucionar 436 consultes a la web de llista UFS
 - [X] 250 queries
- [X] Llista estudis 184 queries
- [X] 234 queries a MPs

# Monitor web del supervisor:

- La idea és tenir alguna eina web que permeti veure que tot està bé a supervisor (s'està executant i executant el que toca)
- https://github.com/mlazarov/supervisord-monitor
- Alertes en cas deixi de funcionar?

# Crear about menu

- [] About menu
- [ ] Informació de la versió de l'aplicació
- [ ] Altres com link als docs

# Laravel websockets


## Implementació
- [X] Configuració ulimit https://docs.beyondco.de/laravel-websockets/1.0/faq/deploying.html
- [ ] https://iesebre.scool.cat/laravel-websockets dona 403 configurar authorization
   - Pendent d'aquest PR: https://github.com/beyondcode/laravel-websockets/pull/28
- [X] Document my solution at https://github.com/beyondcode/laravel-websockets-docs/pull/1
- [X] Documentar la complexitat/problema amb els Broadcast::channel autoritzacions i tenant. Blog?
- [X] provar obrir fireall Laravel port 6001 i utilitzar explotació sense Nginx proxy apuntat a port 6001 com en local
- [X] Comprovar handshake de l'anterior desde locahost.
- [X] Configuració SSL/HTTPS local amb Valet
- [X] Configuració SSL/HTTPS production a servidor Laravel Forge : Certificat Let's encrypt
- [X] Instal·lar supervisor per fer permanent la execució: https://docs.beyondco.de/laravel-websockets/1.0/basic-usage/starting.html#keeping-the-socket-server-running-with-supervisord
- [X] Instal·lar supervisor al servidor explotació
- Multinenant:
  - JAVASCRIPT|FRONTENDSIDE (LARAVEL ECHO amb pusher)
    - MAIN APP: la configuració és estàtica (en temps de compilació)
      - [X] resources/js/bootstrap.js configuració Laravel echo: key: process.env.MIX_PUSHER_APP_KEY,
      - Exemple fitxer: https://github.com/beyondcode/laravel-websockets-demo/blob/master/resources/js/bootstrap.js
    - TENANTS
      - La key (es pot publicar a Javascript no és un secret és un id, oco però no publicar res més) serà diferent per cada tenant i per a la app principal. Com?
      - Disponible a través de window.tenant object:
        - [X] Afegir PUSHER_APP_KEY a window.tenant.pusher_app_key      
  - SERVER SIDE (LARAVEL WEB SOCKETS substituint a pusher)
    - Cal donar d'alta a l'array apps (config/web-socket.php) una app per cada tenant i per principal
       - Exemple fitxer: https://github.com/beyondcode/laravel-websockets-demo/blob/master/config/websockets.php
    - [X] Una entrada fixe per a scool.cat -> main app
    - [X] Mateixa config per a explotació i servidor. NO PROBLEM: el server al que s'apunta "és el mateix"
      - Des de PHP:
        -  https://github.com/beyondcode/laravel-websockets-demo/blob/master/config/broadcasting.php
        - localhost 6001
      - Des dels navegadors:
        - https://github.com/beyondcode/laravel-websockets-demo/blob/master/resources/js/bootstrap.js
        - wsHost: window.location.hostname i wsPort: 6001,     
    - [X] Es pot crear un provider de apps a mida que podria agafar les dades de la taula tenants
      - [X] Afegir camps a la taula tenant:
       - 'id' => env('PUSHER_APP_ID'),
       - 'name' => env('APP_NAME'),
       - 'key' => env('PUSHER_APP_KEY'),
       - 'secret' => env('PUSHER_APP_SECRET'),
       - 'enable_client_messages' => true,
       - 'enable_statistics' => true,

## Desinstal·lació 
Si s'ha de tirar endarrera fitxers a esborrar:
- Esborarr database/migrations/2018_12_09_183944_create_websockets_statistics_entries_table.php
- composer remove beyondcode/laravel-websockets
- Esborrar config/websockets.php
- Recuperar config config/broadcasting.php: Treure de options:

```
+                'host' => '127.0.0.1',
+                'port' => 6001,
+                'scheme' => 'https'
```
- Recuperar configs Laravel echo a main app i tenant:
 - resources/js/bootstrap.js
 - resources/tenant_js/bootstrap.js
Treure:

```
-  key: process.env.MIX_PUSHER_APP_KEY,
-  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
+  key: '6f627646afb1261d5b50',
+  wsHost: window.location.hostname,
+  wsPort: 6001,
+  disableStats: true,
```

```
+  wsHost: window.location.hostname,
+  wsPort: 6001,
+  disableStats: true,
+  encrypted: true
``` 

#Feature Requests

- [ ] Una opció que permeti desactivar el enviament d'emails reals i que s'enviin a mailtrap tan a explotació com local
  -  [ ] En local no enviar mai emails de veritat
- [ ] Canviar color toolbar quan estem a explotació sigui diferent de local per tenir clar on estem treballant
 
#Errors

- [X] Configurar ok pusher a explotació!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 - [X] Comprovar va temps real a https://iesebre.scool.cat/changelog
- [X] When queued emails use scool.cat url instead of iesebre.scool.cat
- [ ] Error als esborrar una incidencia amb comentaris?:
- [ ] Error als esborrar una incidencia amb assignees?: 
- [ ] Error als esborrar una incidencia amb etiquetes:

App\Models\Incident:2
Failed At
18-12-09 17:04:29
Error
Symfony\Component\Debug\Exception\FatalThrowableError: Call to a member function map() on array in /home/sergi/Code/acacha/scool/app/helpers.php:8473
Stack trace:
#0 [internal function]: {closure}(Array, 0)
#1 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Support/Collection.php(1007): array_map(Object(Closure), Array, Array)
#2 /home/sergi/Code/acacha/scool/app/helpers.php(8474): Illuminate\Support\Collection->map(Object(Closure))
#3 /home/sergi/Code/acacha/scool/app/Models/Incident.php(107): map_collection(Object(Illuminate\Support\Collection))
#4 /home/sergi/Code/acacha/scool/storage/framework/views/e0837c84ae738b24c40fd1df2f561175a24e487f.php(2): App\Models\Incident->map()
#5 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(43): include('/home/sergi/Cod...')
#6 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(59): Illuminate\View\Engines\PhpEngine->evaluatePath('/home/sergi/Cod...', Array)
#7 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/View.php(142): Illuminate\View\Engines\CompilerEngine->get('/home/sergi/Cod...', Array)
#8 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/View.php(125): Illuminate\View\View->getContents()
#9 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/View.php(90): Illuminate\View\View->renderContents()
#10 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Markdown.php(61): Illuminate\View\View->render()
#11 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(267): Illuminate\Mail\Markdown->render('tenants.emails....', Array)
#12 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(237): Illuminate\Mail\Mailable->buildMarkdownView()
#13 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(153): Illuminate\Mail\Mailable->buildView()
#14 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\Mail\Mailable->Illuminate\Mail\{closure}()
#15 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(160): Illuminate\Mail\Mailable->withLocale(NULL, Object(Closure))
#16 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(52): Illuminate\Mail\Mailable->send(Object(Illuminate\Mail\Mailer))
#17 [internal function]: Illuminate\Mail\SendQueuedMailable->handle(Object(Illuminate\Mail\Mailer))
#18 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#19 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#20 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#21 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#22 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\Container\Container->call(Array)
#23 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(116): Illuminate\Bus\Dispatcher->Illuminate\Bus\{closure}(Object(Illuminate\Mail\SendQueuedMailable))
#24 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Mail\SendQueuedMailable))
#25 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#26 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\Bus\Dispatcher->dispatchNow(Object(Illuminate\Mail\SendQueuedMailable), false)
#27 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\Queue\CallQueuedHandler->call(Object(Illuminate\Queue\Jobs\RedisJob), Array)
#28 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\Queue\Jobs\Job->fire()
#29 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\Queue\Worker->process('redis', Object(Illuminate\Queue\Jobs\RedisJob), Object(Illuminate\Queue\WorkerOptions))
#30 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\Queue\Worker->runJob(Object(Illuminate\Queue\Jobs\RedisJob), 'redis', Object(Illuminate\Queue\WorkerOptions))
#31 /home/sergi/Code/acacha/scool/app/Console/Commands/WorkCommand.php(108): Illuminate\Queue\Worker->daemon('redis', 'iesebre', Object(Illuminate\Queue\WorkerOptions))
#32 /home/sergi/Code/acacha/scool/app/Console/Commands/WorkCommand.php(92): App\Console\Commands\WorkCommand->runWorker('redis', 'iesebre')
#33 [internal function]: App\Console\Commands\WorkCommand->handle()
#34 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#35 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#36 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#37 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#38 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\Container\Container->call(Array)
#39 /home/sergi/Code/acacha/scool/vendor/symfony/console/Command/Command.php(255): Illuminate\Console\Command->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#40 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#41 /home/sergi/Code/acacha/scool/vendor/symfony/console/Application.php(901): Illuminate\Console\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#42 /home/sergi/Code/acacha/scool/vendor/symfony/console/Application.php(262): Symfony\Component\Console\Application->doRunCommand(Object(App\Console\Commands\WorkCommand), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#43 /home/sergi/Code/acacha/scool/vendor/symfony/console/Application.php(145): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#44 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\Component\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#45 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#46 /home/sergi/Code/acacha/scool/artisan(37): Illuminate\Foundation\Console\Kernel->handle(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#47 {main}
Next ErrorException: Call to a member function map() on array (View: /home/sergi/Code/acacha/scool/resources/views/tenants/emails/incidents/deleted.blade.php) in /home/sergi/Code/acacha/scool/app/helpers.php:8473
Stack trace:
#0 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(47): Illuminate\View\Engines\CompilerEngine->handleViewException(Object(Symfony\Component\Debug\Exception\FatalThrowableError), 0)
#1 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(59): Illuminate\View\Engines\PhpEngine->evaluatePath('/home/sergi/Cod...', Array)
#2 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/View.php(142): Illuminate\View\Engines\CompilerEngine->get('/home/sergi/Cod...', Array)
#3 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/View.php(125): Illuminate\View\View->getContents()
#4 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/View/View.php(90): Illuminate\View\View->renderContents()
#5 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Markdown.php(61): Illuminate\View\View->render()
#6 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(267): Illuminate\Mail\Markdown->render('tenants.emails....', Array)
#7 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(237): Illuminate\Mail\Mailable->buildMarkdownView()
#8 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(153): Illuminate\Mail\Mailable->buildView()
#9 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\Mail\Mailable->Illuminate\Mail\{closure}()
#10 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(160): Illuminate\Mail\Mailable->withLocale(NULL, Object(Closure))
#11 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(52): Illuminate\Mail\Mailable->send(Object(Illuminate\Mail\Mailer))
#12 [internal function]: Illuminate\Mail\SendQueuedMailable->handle(Object(Illuminate\Mail\Mailer))
#13 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#14 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#15 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#16 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#17 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\Container\Container->call(Array)
#18 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(116): Illuminate\Bus\Dispatcher->Illuminate\Bus\{closure}(Object(Illuminate\Mail\SendQueuedMailable))
#19 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(Illuminate\Mail\SendQueuedMailable))
#20 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#21 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(49): Illuminate\Bus\Dispatcher->dispatchNow(Object(Illuminate\Mail\SendQueuedMailable), false)
#22 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(83): Illuminate\Queue\CallQueuedHandler->call(Object(Illuminate\Queue\Jobs\RedisJob), Array)
#23 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(327): Illuminate\Queue\Jobs\Job->fire()
#24 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(277): Illuminate\Queue\Worker->process('redis', Object(Illuminate\Queue\Jobs\RedisJob), Object(Illuminate\Queue\WorkerOptions))
#25 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(118): Illuminate\Queue\Worker->runJob(Object(Illuminate\Queue\Jobs\RedisJob), 'redis', Object(Illuminate\Queue\WorkerOptions))
#26 /home/sergi/Code/acacha/scool/app/Console/Commands/WorkCommand.php(108): Illuminate\Queue\Worker->daemon('redis', 'iesebre', Object(Illuminate\Queue\WorkerOptions))
#27 /home/sergi/Code/acacha/scool/app/Console/Commands/WorkCommand.php(92): App\Console\Commands\WorkCommand->runWorker('redis', 'iesebre')
#28 [internal function]: App\Console\Commands\WorkCommand->handle()
#29 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(29): call_user_func_array(Array, Array)
#30 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#31 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#32 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Container/Container.php(572): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#33 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Console/Command.php(183): Illuminate\Container\Container->call(Array)
#34 /home/sergi/Code/acacha/scool/vendor/symfony/console/Command/Command.php(255): Illuminate\Console\Command->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#35 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Console/Command.php(170): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#36 /home/sergi/Code/acacha/scool/vendor/symfony/console/Application.php(901): Illuminate\Console\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#37 /home/sergi/Code/acacha/scool/vendor/symfony/console/Application.php(262): Symfony\Component\Console\Application->doRunCommand(Object(App\Console\Commands\WorkCommand), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#38 /home/sergi/Code/acacha/scool/vendor/symfony/console/Application.php(145): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#39 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Console/Application.php(89): Symfony\Component\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#40 /home/sergi/Code/acacha/scool/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Illuminate\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#41 /home/sergi/Code/acacha/scool/artisan(37): Illuminate\Foundation\Console\Kernel->handle(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#42 {main}

# Bugs

- [ ] No funcionen les routes api als testos però si les routes web??
  - [ ] Al desfer els canvis d'un composer update s'ha arreglat. Composer.lock file que funciona guardat a composer.lock.ok
  - Paquet dona error: Updating symfony/http-foundation (v4.1.7 => v4.2.1): Loading from cache

Crec estic afectat per aquesta issue:

- https://github.com/symfony/symfony/issues/29478 

# Maximum size exceeded pusher | laravel-websockets

- [ ] Torno a tenir problemes amb algunes incidències crec són tenen comentaris
- [ ] No utilitzar pusher? https://github.com/beyondcode/laravel-websockets

# Laravel ide helper i Laravel debugbar

- [X] Laravel ide helper i Laravel debugbar instal·lats amb llum. NO fer-ho amb llum i utilitzar el que digui la doc dels paquets 
- [ ] No puc executar php artisan ide-helper:models Class '\Venturecraft\Revisionable\Revision' not found


# Eager Loading

- [X] https://iesebre.scool.test/changelog Solucionar problema de performance executa un munt de queries. Eager Loading.
- [X] https://iesebre.scool.test/incidents: OCO també té moltes consultes

# LOGGER

- [X] IncidentLogger.php No cal que estigui a Listeners No? De fet no és un listener!  

# Laravel Queues

- [X] LogIncidentEvent -> Peta amb casos en que s'esborra la incidència. Forma en que recupear Laravel els models esborrats quan hi ha cuas
 - [X] Eliminar comentari
 - [X] Eliminar incidència 
- [X] Problemes amb old_value i new value a tots els events modificació. Tot i que old_value és un clone el model la forma 
en que Laravel restaura els models (executant una consulta a la base de dades) fa que siguin el mateix objecte 

Idea:
- [X] No utilitzar clone pels valors antics utilitzar map()
- [X] Quan es tracti de models esborrats o que s'esborraran no passar els models als events sinó l'array map() del model

## Multitenant

- [X] Solució:
  - IMPORTANT: Cada cop hi ha canvis al codi cal reiniciar supervisor!!!
  - Una queue per a cada tenant
  - Cal crear un supervisor per cada tenant:
  - Comanda artisan queu:work adaptada a tenants:
    - php artisan queue:work:tenant {tenantname} {conection} 
    - Exemple: php artisan queue:work:tenant iesebre redis ...
  - Fitxer /etc/supervisor/conf.d/scool_worker_iesebre.conf

```
sudo cat /etc/supervisor/conf.d/scool_worker_iesebre.conf
[program:scool_worker_iesebre]
process_name=%(program_name)s_%(process_num)02d
command=php /home/sergi/Code/acacha/scool/artisan queue:work:tenant iesebre redis --queue=iesebre --sleep=3 --tries=3 --delay=3
autostart=true
autorestart=true
user=sergi
numprocs=8
redirect_stderr=true
stdout_logfile=/home/sergi/Code/acacha/scool/storage/logs/worker_iesebre.log
```

   - Cal enviar les tasques a la cua/tenant que correspongui es pot utilitzar tenant_from_current_url():

```
Mail::to($event->incident->user)
            ->cc(Setting::get('incidents_manager_email'))
            ->queue((new IncidentCreated($event->incident))->onQueue(tenant_from_current_url()));
```

# Bugs

- [X] 404 a totes les URL: S'ha de crear el tenant: php artisan migrate:fresh --seed

  
# Laravel Telescope

- [ ] Torna a forçar usuari de sistema sigui App\User en comptes de App\Models\User i falla tot. NO ES POT INSTAL·LAR
Notes per esborrar:
- config/app.php treure el service provider
- Dont discover a composer.json: "laravel/telescope"
- composer remove laravel/telescope
- /public/vendor/telescope
- /config/telescope.php

# Laravel Horizon

- [X] Instalar
- [X] Notes Uninstall:
  - Remove HorizonSErviceProvider de config/app.php
  - Remove boostrap/cache/packages file
  - composer remove laravel/horizon
  - remove config/horizon.php file
  - remove public/vendor/horizon
  - create_failed_jobs migrate

# Mòdul professorat

## Add

Moodle:
- [ ] Afegir compte de Moodle

## List/datatables

### Comptes d'usuari:

#### Moodle

- [X] MoodleUser de entity canviar tots els usos a MoodleUser a App\Models
- [ ] Carpeta app/Moodle crec no cal!

Casos
- El professor no té compte de Moodle
  - [ ] Mostrar botó afegir 
- El professor té compte de Moodle
 - [ ] Mostrar links:
   - [ ] Perfil de l'usuari a Moodle
   - [ ] Modificació/Edit a Moodle
   - [ ] Mostrar info de l'usuari a moodle -> Moodle SHOW -> dia
- Hi ha algún problema de sincronització:
 - Coincideixen uidnumbers però no les dades: email|username i Nom i lastname
 - No hi ha cap usuari de Moodle amb el uidnumber corresponent però sí que hi ha un usuari amb username   
 - [ ] Mostrar alerta/botó i permetre sincronitzar

TeachersController i TeachersControllerTest:
-  Afegir més info sobre els teachers relacionada amb Moodle:
  - [X] moodle_id -> El id de Moodle del teacher (si el té)
  - [X] moodle_id = null sí no en té
  - [ ] Testos:
     - [ ] Funció check_teacher ha de comprovar camps moodle_id
- [ ] Que passa si no hi ha mòdul Moodle activat???

HELPERS I SEEDERS
- [ ] Creació de professors associar usuari de Moodle. Taula external_users camp moodle_id

# Mòdul usuaris

## Usernames

- [X] A moodle utilitzar correu electrònic com a nom usuari (no cal càlcul usernames)
  - [X] Moodle -> usuaris com emails
    - [X] https://www.iesebre.com/moodle/admin/settings.php?section=sitepolicies
    - [X] Permet caràcters estesos en els noms d'usuari
- [ ] Càlcul centralitzat del username?
- [ ] On guardar el username -> base de dades camp únic?
- [ ] no preguntar mai al usuari -> calcular
- [ ] Assignar durant la creació del registre usuari (al registrar o crear l'usuari de qalsevol altre manera)
- [ ] Es fa una proposta de nom usuari però es comprova si algú ja la té

# Moodle . MOODLE USERS

## Moodle users Lists

- [X] Eliminació massiva d'usuaris

## Relació amb USERS:
- idnumber: camp de moodle s'hauria d'utilitzar per a relacionar amb usuaris locals
- TEMA EMAIL: QUIN UTILITZAR?
- Sincronitzar: 
 - [ ] Coincideixi nom local amb nom a Moodle
 - [ ] Avatar/Photo

INFO MOODLE USER:

"id": 1
 "username": "guest"
 "firstname": "Guest user"
 "lastname": " "
 "fullname": "Guest user "
 "email": "root@localhost"
 "department": ""
 "firstaccess": 0
 "lastaccess": 0
 "auth": "manual"
 "suspended": false
 "confirmed": true
 "lang": "en"
 "theme": ""
 "timezone": "99"
 "mailformat": 1
 "description": "This user is a special user that allows read-only access to some courses."
 "descriptionformat": 1
 "profileimageurlsmall": "https://secure.gravatar.com/avatar/b1a4b2518dbbdd47dd4a713d5cd1df94?s=35&d=mm"
 "profileimageurl": "https://secure.gravatar.com/avatar/b1a4b2518dbbdd47dd4a713d5cd1df94?s=100&d=mm"

MOODLE USERS LIST:
- [X] Accions posar en components externs
- [ ] Guest user limitar accions, no es pot mirar perfil, no es pot esborrar
- [ ] Similar amb usuari admin
- Operacions massives:
  - [ ] Eliminació
    - [X] Interfície està preparada
    - [ ] Cal fer api delete multiple users
- Filtres:
  - [X] Tipus authenticació
  - [X] Sense usuari local
  - [X] Amb usuari local
  - [X] No sincronitzats
  - [X] Sincronitzats
  - [X] Confirmat
  - [X] No confirmat
  - [X] No ha entrat mai
- [X] Color de la toolbar i dense
- [X] Estil de la taula més dens?

Altres:
  - [X] Camp idnumber sigui un link al perfil de l'usuari local amb aquest id=idnumber
  - [ ] Últim accés tingui un title que mostri la data exacte d'últim accés
  - [ ] Importar usuari de Moodle a Local:
    - [ ] Crear usuari utilitzant correu de Moodle i fullname com a name
    - [ ] Crear person a partir de firstname i lastname (autopartir en sn1 i sn2). Altres dades?
  - [ ] Al mostrar el uidnumber que sigui un link a la fitxa (show) d'usuari local
  - [X] Mostrar el avatar local per poder comparar amb el avatar de Moodle si hi ha uidnumber
  - [] Mostrar també dades locals de la persona: givenName, sn1 i sn2 

RELACIÓ AMB MODELS EXTERN USUARI LOCAL

- [X] Al crear usuaris de moodle si es fa a partir d'un usuari local utilitzar email corporatiu com a usuari de Moodle
- 


Millores:
- [ ] Al crear un usuari de Moodle no mostrar els usuaris locals que ja tenen un usuari de Moodle al desplegable
- [ ] Tipus d'usuari:
  - [ ] El pas i secretaries i pares i altres no tenen/no necessiten usuari de Moodle
- [ ] Treure de Javascript les URLs hardcoded a iesebre.com agafar-les del fitxer de config de config/moodle.php

Migració:
- [ ] Caldria canviar tots els usuaris actuals de moodle (sense modificar el id) a que utilitzin com a username compte de correu
  - [ ] Quina compte de correu? 
    - [ ] Username és el ompte institucional
    - [ ] Email de moodle: ??? personal o institucional?
  - [ ] TODO
  
Edit:
- [ ] Opcions relacionades amb la edició i sincronització amb usuari local
- [ ] Canviar username?
- [ ] Canviar email (només si no hi ha uidnumber)
- [ ] Sincronitzar email (si hi ha uidnumber i no coincideixen)
- [ ] Canviar nom (només si no hi ha uidnumber)  
- [ ] Sincronitzar nom (només si hi ha uidnumber i no coincideixen)  

Importar:
- [ ] Importar usuari de Moodle a usuari local

Exportar:
- [ ] Usuaris Locals a usuari de Moodle
  - [X] Al crear un nou usuari o podem fer a partir d'un usuari local
  - [ ] Mostrar llista usuaris locals no tenen usuari de Moodle

Avatar:
- [ ] Sincronitzacións i actualitzacions del Avatar
  - [ ] Al crear un usuari de Moodle des de l'usuari local
  - [ ] Des de la llista sincronitzar/Actualitzar  
  
Seguretat:
- [ ] No es pot eliminar usuari guest ni usuari admin
- [ ] Config amb una llista altres usuaris no es poden esborrar (config/moodle.php)

Change password:
Case 1) Usuari de Moodle té compte local scool
- [ ] Mostrar diàleg per canviar password:
  - [ ] Switch amb dues opcions escollir password o establir automàticament
  - [ ] Camp user password si s'indica establir la paraula de pas
Case 2) Usuari de Moodle no té compte local scool
- [ ] Igual que l'anterior però no es canvia password local
  - [ ] s'utilitza el mail per enviar el email a l'usuari amb la nova paraula de pas

Bugs:
- [X] Reduir les 506 queries amb Eager Loading

Mòdul:
- [ ] Afegir mòdul a la base de dades

Controladors web
- [X] Controlador web i Test
- [X] Tots els permisos i rols comprovats als tests i creats a helpers.php
- [X] Afegida opció de menú a la taula menus
- [X] Tots els testos marcats com group moodle i slow per no executar-los sempre
Controlador API:
- [X] Refresh/index
- [X] Remove
- [X] Add/store
- [ ] Edit

TODO operacions pendents de mirar a la API:

https://www.iesebre.com/moodle/admin/webservice/documentation.php
- auth_email_get_signup_settings
- auth_email_signup_user
- core_auth_confirm_user
- core_user_add_user_device: Push Notifications???
- core_user_agree_site_policy?
- core_user_update_picture

Llista usuaris Moodle:

TROUBLESHOOTING:
- [ ] Ara al eliminar un usuari de Moodle s'eliminar Moodle però dona error en local
  - [ ] Error deu ser per procés o event 
- [ ] Eliminació usuari de Moodle amb compte local associada:
  - [ ] MOstrar doble confirmació: té compte local associada -> Si tireu endavant aquesta associació s'eliminara

- Accions pendents:
  - [ ] Tornar a enviar email paraula de pas (generar nova)
  - [ ] Editar. core_user_update_users
  - [ ] Invalidar/suspendre compte. Actualment és el que fa la syunc ldap si troba l'usuari a Moodle però no a Ldap
  - [ ] Confirmar compte
  - [ ] Sincronitzar avatars (core_user_update_picture)
- [X] Correu electrònic sigui un link a Gmail per enviar un email des de Gmail
- [X] No utilitzar noms en anglès als headers  
- [X] Juntar camps avatar i username en un sol camp
- [X] Link edita el perfil a MOodle: https://www.iesebre.com/moodle/user/editadvanced.php?id=5820&course=1&returnto=profile
Checks:
- [ ] Buscar usuaris desincronitzats
- [ ] Buscar usuaris de Moodle sens idnumber pèro que coincideix email/username i sincronitzar  
- [ ] Usuaris amb idnumber però sense id local corresponent
 

MOODLE password recovery:
- Usuaris ldap -> no tenen password el busquen a Ldap però també estan a la base de dades (amb passwrd buit)
- El webservice només deixa posar password a partir de text en clar no podem sincronitzar hashes
- Webservice si té una opció per crear la paraula de pass i enviar per email

Altres:
- https://stackoverflow.com/questions/47688746/create-user-in-moodle-through-web-services-php
- He creat rol scool: https://www.iesebre.com/moodle/admin/roles/define.php?action=view&roleid=9
- https://www.iesebre.com/moodle/admin/settings.php?section=webservicesoverview

Desactivar edició del perfil a Moodle:
- [ ] https://docs.moodle.org/35/en/Roles_FAQ#How_can_I_prevent_a_user_from_changing_their_own_password.3F
  - [ ] ES pot fer a partir dels permisos s'ha de treure el permis: moodle/user:editownprofile
  - [ ] El canvi del password ja està desactivat al posar una URL a ebre-escool...
  - [ ] Rol usuari autenticat: https://www.iesebre.com/moodle/admin/roles/define.php?action=view&roleid=7
    - [ ] Treure permis: https://docs.moodle.org/2x/ca/Capabilities/moodle/user:editownprofile
Llista usuaris Moodle:
- [ ] Quins usuaris de Moodle estan sincronitzats/existeixen a scool com a users
  - [ ] Camp email per sincronitzar
  - [ ] Altres camps possibles? idnumber
  - [ ] Al menu opcional de tres punts es podria afegir un apartat per mostrar alertes/inconsistències:
    - [ ] Usuaris que tenen un idnumber però després no coincideixen els dades del Moodle i el usuari local amb id =idnumber
  - [ ] De fet podria apareixer una icona campana o similar en roig indicant quan hi han alertes
  
## BUGS
  GESTIÓ USUARIS MOODLE
  
  - [X] "Error de xarxa" al esborrar usuari de Moodle
  - [X] Refresh dona error: ' Cannot read property 'join' of undefined"'


# Permisos

- [X] Usuaris IncidentsManager no poden tancar incidències dels altres usuaris
- [X] Usuaris IncidentsManager no poden afegir usuaris al mòdul Incidències pq no mostra cap usuari el desplegable pq no són
UsersManagers
 - [X] Mostra la llista d'usuaris però no permet afegir rol IncidentsManager (si Incidents)
- [ ] Usuaris IncidentsManager no poden mostrar incidències (apareix blanc el dialeg show)
  - [X] Se soluciona tenint els dos rols IncidentsManager i Incidents
  
# BUGS

- [ ] php artisan route:list s'executa superlent? Alguna operació que realitzem no s'hauria de fer des de consola?

# Laravel Passport

- [ ] Les rutes de Laravel passport (Passport:routes() a boot method AuthServiceProvider) no estan dins middleware tenant
      NO POT PASSAR MATEIX QUE AMB BROADCAST?
   - [ ] Request->user() null amb peticions XHR i Laravel passport?   

# DOCS

- [X] Crear un projecte amb els docs en format markdown que sigui copia de Laravel docs
- [X] Crear un projecte amb la web que mostra els docs tipus laravel.com
- A cada mòdul posar una icona help que porti a la documentació
  - [X] Mòdul incidències
- [ ] Adaptar tota la web de documentació de Laravel a scool.cat
- [ ] Documentació Mòdul incidències
  - [ ] El fitxer que hi ha ara és routing de Laravel -> posar docs propis  

# Users management

## Login and Register Events

- [ ] Esdeveniments del mòdul UsersManager
  - [ ] Un usuari ha demanat canviar la paraula de pas -> NO HI HA ESDEVENIMENT!
  
  
# Menu

- [] CRUD DE MÒDULS -> poder gestionar mòduls activats/desactivats

# ChangeLog Module

## Revisionable vs Custom solution

**IMPORTANT** [ ] JA TENIA MÒDUL????    

https://github.com/VentureCraft/revisionable

A la home hi ha una "antiga" solució pendent de ser esborrada:
- AuditLogComponent: datatable mostra les dades
- HomeController: 
    
```
    protected function auditLogs() {
                          return collect(RevisionResource::collection(
                              Revision::orderBy('created_at', 'desc')->with(['user','revisionable'])->get()));
                      }
```

TODO:
- Tinc doncs una versió ja feta amb un objecte REvision basat https://github.com/VentureCraft/revisionable/tree/master/src/Venturecraft/Revisionable
- Tinc docs taules changelog i revisions
- [ ] ELIMINAR UNA DE LES DOS VERSIONS UN COP FET EL MERGE

## Tasques pendents mòdul changelog

- [X] Crear entrada de menú i la corresponent entrada a la taula de base de dades
- [ ] Crear fitxer de settings (config/changelog.php) del mòdul

Settings:
- [ ] TODO? Duració dels registres i neteja

Backup/Neteja
- [] Permetre netejar registres vells i fer backup dels registres

Performance:
- [ ] Control d'esdeveniment en segon terme (activar i utilitzar queues)

Idees:
- [ ] Base de dades o memòria ràpida tipus Redis?

Vista:
- [ ] Esdeveniments no associats a cap usuari? -> No donar error pq usuari pot ser opcional
- [ ] Objecte registrable -> Copia persistent de l'estat de l'objecte en aquell moment (camp Json, guardar map() de l'objecte)
- [ ] Filtres (només quan es crida el component com ChangelogManager o superadmin):
  - [ ] Filtrar per usuari
  - [ ] Filtrar per mòdul
- [X] Utilitzar Data Iterator amb el registre de canvis?
  - [ ] Aconseguir fer funcionar l'animació que funciona sense data iterator però no amb data-iterator: v-slide-x-transition group
  
Testos:
WEB:
- [X] ChangeLogControllerTest:
  - [X] Mostra la vista que correspon amb les dades que pertoquen
  - [] TODO Limitar nombre de dades de la vista

  
# Incidents Manager. Gestor d'incidències

# Permisos
- [ ] Els usuaris normals d'incidències poden veure el botó assignar etiquetes. NO HAURIEN
- [ ] Els usuaris normals d'incidències poden veure el botó assignar usuaris. NO HAURIEN

## RESPONSIVE
- [ ] Versió Mobile: Datatables canviar per un Data Iterator de Cards (una incidència un card)

## CHANGELOG

Changelog d'una incidència a la vista Show:
- [ ] Barrejar els comentaris i les accions com fa Github i mostrar missatges intercalats (i ordenats per temps) amb operacions com usuari tal a tancat la incideència
- [ ] Utilitzar vista vuetify timeline per mostrar tant els comentaris com l'historial
- [ ] Comentaris i registre de canvis en temps real a la vista show

Changelog:
- [ ] Filtre extra: Registre de canvis per a un objecte (igual que per User o per a mòdul amb URL propia)
- [ ] Afegir un botó a cada incidència que permeti veure el changelog -> Hi ha un changelog a cada incident i per tant es pot fer amb Dialog
sense necessitar d'executar nova URL ni fer cap petició extra XHR

PUSHER ALGUNS OBJECTES TENEN MASsa INFO I DONEN PROBLEMES:
- [ ]The data content of this event exceeds the allowed maximum (10240 bytes).
  - [ ] Repassar objectes a map com per exemple user quan hi ha camps com user_name, user_email. Ocupen MOLT ESPAI!
  - [ ] Camps description -> Enviar un resum 
- See http://pusher.com/docs/server_api_guide/server_publishing_events for more info

Estadístiques:
- [ ] Temps mig tancament incidències (Auditories)
- [ ] Totals per tipus (obert tancat)
- [ ] Totals per usuaris
- [ ] Total per departament
- [ ] Gràfiques/quesitos
- [ ] Exportació de dades incidències a CSV
- [ ] Marges temporals: lliure marge però predefinits (any natural- any acadèmic)

EXTRES:
- [ ] Floating button afegir comentari al mostra una incidència: potser múltiples accions
- [ ] Afegir botó normal Afegir Incidència (a part del flotant) a la llista d'incidències

Settings:
- [ ] TODO fer anar lo d'activar o no el mòdul
  - [ ] Desactiu? Dos formes-> no mostrar o mostrar un missatge que està desactivat temporalment

Etiquetes:
- [ ] CRUD etiquetes per als managers
  
BUGS:
- [ ] OCO changelog a Incidents és una relació que pot provocar Bucle
- [ ] Els botons afegir comentari i afegir comentari i tancar al estar en loading i disabled desapareixent en comptes 
de mostrar el loading

## Comentaris

- [ ] Canviar la interfície a la nova timeline de vuetify (vegeu exemple advanced):
- [ ] Afegir l'historial i no només els comentaris (registres de canvis per al modle/incidència concret)

- https://vuetifyjs.com/en/components/timelines
- https://github.com/vuetifyjs/vuetifyjs.com/tree/master/src/examples/timelines/advanced.vue

## Idees

- Funcionalitat PING! Com està la incidència? Ara és pot fer amb un nou comentari però com resaltar-lo?

## Filtres

- [ ] On sóc mencionat. Depèn implementar mencions (@username)
  - https://laracasts.com/series/whatcha-working-on/episodes/33 
- [X] Buscador -> Full text search field. DE MOMENT NO CAL ES POT BUSCAR PER TOT LO NECESSARI
  - [ ] Permetre buscar per estat oberta/tancada (camp full search amb tots els strings de cerca a actions )

## Assignacions
- [ ] Settings: poder indicar les persones a les que és més habitual assignar incidències
  - [ ] Sortiran les primeres a la llista de possibles assignees

## Etiquetes
- [ ] Interfície web CRUD per a crear etiquetes integrada al desplegable etiquetes

## Tancament incidències

- [ ] Camp solved_by per saber qui l'ha resolt?


## Notificacions/comunicació**

MENU PRINCIPAL INCIDENCIES:
- [ ] Mostrar un badge que indiqui las incidència noves (des de l'últim login?)
ALTRES:
- [ ] TODO: a la app o pàgina HTML (permetre notificacions al navegador) -> Service Workers
- [ ] Com Github tenir un botó que permeti unsubscribe to notifications
- [ ] Telegram?

## Altres
- [ ] Datatables utilitzar expand per mostrar més info sobre la incidència? Comentaris? Descripció completa?


## Ideas taken from Github
- [ ] Altres extres interessants: @mencions Links HTTP, etc
- [ ] Poder fer referència+link a un altre incident/issue amb #numissue
- [ ] Apartat participants: gent que participa de la discusió/comentaris

# CURRICULUM

TODO:
- [X] Etiquetes d'estudis que cal crear:
  - [X] LOE/LOGSE
  - [X] FP/Cursos d'accés
  - [X] Grau mitjà o Grau superior
- [ ] Estaria bé disposar d'una icona per cada estudi   
- [ ] Per les famílies hi ha una imatge associada a la web de la gene: http://queestudiar.gencat.cat/ca/estudis/fp/cicles-families/act-fisiques-esportives/?p_id=46&estudi
  - http://www.todofp.es/sobre-fp/informacion-general/centros-integrados/nuevos-centros/familias.html

Afegir Subject/UF:
- [ ] Relació entre desplegables/filtres
  - [ ] Llista de Mòduls professional -> Filtrar i només veure els corresponents a l'estudi seleccionat previàment 
  - [ ] Llista de cursos -> Filtrar i només veure els corresponents al curs seleccionat previàment
- Navegacions:
  - [X] Crear un studi sinó existeix des de la llista d'estudis
- [X] Camp number: seleccionar per defecte el pròxim número d'UF seguent que pertoca
  - [X] Mostrar una alerta si el Mòdul Professional té número de UFS indicat i ja estan totes creades  
   
Crear Estudi:
- Camps pendents:
- [X] Número de mòduls (13)
- [ ] Dual sí/no?
- [ ] Imatge a mostrar a la llista pública
- [ ] Link al tríptic
 
## Vistes públiques:

- [ ] Reproduir un format web consultable del currículum similar a la web
- [ ] Cicles/Estudis per família professional i Grau Mitjà o Superior
- [ ] Camps extres estudis
  - [ ] Link al tríptic: https://www.iesebre.com/documents/estudis/triptics/dam.pdf
  - [ ] Exemple estudi a la web: https://www.iesebre.com/index.php/continguts/23-cfgs/455-dam?showall=1
    - [ ] Info camps que falten a la base de dades:
       - [ ] Durada en cursos academics -> Relació amb cursos (camps computat)
       - [ ] Horari -> També calculat a partir dels cursos?
       - [ ] Hores de FCT
       - [ ] Dual?
  - Vistes:
    - [ ] Pla docent (Taula Mòduls UFS i hores ) https://www.iesebre.com/index.php/continguts/23-cfgs/455-dam?showall=&start=1
    - [ ] Textos:
       - [ ] Aprèn treballant | Què faràs | De què treballaràs
       - [ ] Deixar Obert? Utilitzar miniword o similar   
  - [ ] Upload del tríptic
  - [ ] Altres documents?

## CURRICULUM MODULE


- [ ] initialize_fake_subjects hi ha uns studies incorrectes donats d'alta tenen cap law_id. Arreglar
- [ ] Feature Test web Controller -> CurriculumTest
 - [X]  Permisos usuaris no logats, usuaris regulars, admin i Manager
 - [X] Comprovar retorna vista que toca i amb les dades que toca
 - [X] create_sample_studies
- [ ] Trobar icona barret estudiant (més similar cast for education)
  - [ ] Activar altres moduls icones Vuetify
- [ ] Depèn del mòdul de professors: aquest mòdul crear els usuaris professors, els departaments i els càrrecs
- [X] Donar d'alta el mòdul a la base de dades
- [X] Entrada de menú
- [ ] Crear Rols associats
  - [X] Curriculum: poden accedir al mòdul (després depèn altres permissos i rols podran fer més o menys operacios)
     - [ ] Permissos assignats al rol
  - [X] Curriculum Manager
     - [ ] Permissos assignats al rol
  - [ ] Assignar a Cap estudis el rol Currículum Manager
  - [ ] Assignar a tots els professors el rol de Currículum
- [X] Mostrar llista estudis del centre
  - [ ] Filtres
    - [X] Per departament principal
    - [X] Per família
    - [ ] Per departament (FOl per exemple hauria de sortir a tots els estudis si se selecciona)
- [ ] Seguretat i autorització
  - [ ] Cap estudis (Curriculum Manager) igual que superadmin pot fer tots
  - [ ] Cap de departament. Pot entrar i veure tot i a més modificar els estudis propis. Tenir en compte hi ha registre de canvis
     - [ ] Limitar algunes operacions: eliminar per exemple
     - [ ] No pot crear estudis
  - [ ] Professor. Pot entrar i veure curriculum
    - [ ] No pot modificar estudis
    - [ ] No pot crear estudis    
    - [ ] no pot eliminar
  - Regular users: no poden entrar al mòdul  
  - [X] Eliminar estudi
    - [ ] Els usuaris sense permisos no poden eliminar estudis i no poden veure la icona d'esborrar
  - [X] Mostrar estudi
      - [ ] Els usuaris sense permisos no poden veure accions edició a la vista show
  - [X] Etiquetes
    - [ ] Els usuaris sense permisos no poden assignar ni dessasgnar etiquetes
  - [X] Assignar departament als estudis
      - [ ] Els usuaris sense permisos no poden assignar ni dessasgnar estudis
  - [X]Assignar familia als estudis
      - [ ] Els usuaris sense permisos no poden assignar ni dessasignar families          
Vistes secundàries:
- [ ] Registre de canvis: tot el mòdul! Canvis a estudis, ufs o qualsevol cosa relacionada ha d'apareixer aquí 
- [ ] Vista UFS-SUBMODULES (mostrar-les al botó ... )
  - [ ] Crear link des de la vista primaria
  - [ ] Todo similar a estudis tema rols:
    - [ ] Algunes adaptacions com que els professors puguin editar les UFs assignades 
- [] Vista MPS-MODULES

Tot gira entorn una unitat bàsica d'ensenyament:
- Actualment aquesta unitat és la Unitat Formativa (abans era el Mòdul Professional)

Dades d'una UF:
- Hores totals de la UF
- Número UF (1,2,3)
- Codi únic
- Nom
- Nom llarg
- Descripció/notes
- Mòdul professional ID
- Study al que pertany la UF
- Course de la UF
- Tipus: Ordinaries, FCT, Sintesi, Transversals (FOL/Anglès)
- Datetime inici i datetime fi: això permet canviar l'ordre de les UFS -> valor indicatiu no real (per tema grups)
-- Aquí tenim un problema amb múltiples grups poden tenir petites diferències en les dades d'inici i fi
-- Per tant no va aquí la info fa a les lliçons potencials

CURRICULUM és fix (o canvia poc cada any)

## Classrooms

- [ ]Canvien segons la matrícula. De fet durant la matrícula els alumnes no decideixen grup i sovint no se sap ni els grups que es faran
- [ ] Evidentment hi ha una planificació o s'esperà una planificació però per molta matrícula es pot amplica nombre grups o reduir
- [ ] Hi ha assignatures com FOL o Anglès que fan classe a múltiples grups al mateix temps
- [ ] Al calcular el potencial a omplir cal
- [ ] Torn matí o tarda?

## Gestió del curriculum 

Característiques principals:

- El conceptes siguin el més generics possibles i adaptables a canvis i no pensar només en la FP
- FP FIRST DEVELOPMENT (com Mobile First però sense oblidar altres dispositius/tipus estudis)

ENTITATS

- CENTRE: Un tenant té un centre per defecte però podria gestionar múltiples centres
- ESTUDI: (exemples DAM, ASIX, FARMACIA, CURS PONT)
- SUBDIVISIONS DELS ESTUDIS
- modules -> firt level (no té pq coincidir) <- veure-les com agrupacions de UFs
- submodules -> firt level (no té pq coincidir) <- Principal
- Tercer nivell TODO
- CURS: UN ESTUDI POT ESTAR DIVIDIT EN UN O MES CURSOS  
  - CICLE: conjunt de cursos (no teni a la fp)
- TIPUS_ESTUDI: FP, CURS PONT, ETC -> De fet és com una etiqueta que podem posar a un estudi
  - LOE/LOGSE o la llei pot ser un altre etiqueta
  - 1 estudi n etiquetes: LOE i FP per exemple
- Families: agrupacions d'estudis, es com un tipus d'estudi però OCO pot tenir dades especifiques

TEACHERS MODULE INTERSECCIÓ AMB CURRICULUM
- ESPECIALITATS:
  - Una especialitat pot estar associada a un professor però també a una UF
- Departaments:
  - Associat a un professor però també a un estudi
  - Per cada estudi hi ha un departament responsable PRINCIPAL de l'ESTUDI
  - Hi ha UFS/MÒDULS que poden tenir múltiples departaments, el principal associat a l'estudi i altres (assingatures transversals, Folm, Angles)
  - Estudis donats per múltiples departaments això pot apareixer aviat com idea d'algun pensador

Rols: Cap estudis
- Wizard per donar d'alta el currículum d'un centre
- Possibilitat de repartir la feina amb caps de departament i caps estudis (però sempre podrà fer-la tota)

- Centre: Cada tenant un centre? Múltiples centres possibles?

- ASIX
- DAM
    
### Wizard (boto add)

0) Desplegable amb els centres: per defecte un sol centre (creat durant la creació del tenant) però podria tenir més centres
1) [ ] Estudi que es vol modificar o que es vol crear
  - [ ] Mostrar una llista estudis existents-> es pot seleccionar un existent i continuar o refer tot el wizard
  - [ ] Camps formulari alta: Nom (únic), codi (únic), Familia
    - [ ] Mòdul de professors: CRUD de departaments
    - [ ] Departament principal associat
    - [X] No poder assignar departaments secundaris -> És un camp computat indirecta serà a partir de les UFS assignades al mòdul
    - Etiquetes (cap o n):
      - [ ] Tipus estudi -> etiquetes
      - [ ] Llei associada (3 valors, LOE, LOGSE o cap valor associat)
    - [ ] Altres camps opcionals: Links associats / Docs associats al estudi
2) [ ] UFS
  - Saltem les MPS ja que són grups de UFS
  - [ ] Mostrar llista (datatables?)ufs del study
  - [ ] Afegir UF
    - [ ] Camps obligàtoris: Codi, nom, nomcurt | Opcionals: descripció | order
    - [ ] MP de la UF (obligatori): 1 a 1 i Combobox: associar un existent o afegir un de nou
      - [ ] Etiquetes mòduls/MP: Normal, Extern (FOL), Síntesí, FCT
    - [ ] Curs en que s'imparteix  
    - [ ] Altres:
      - [ ] Hores Totals
      - [ ] Hores setmanals
      - [ ] Start date      
      - [ ] End date
    - [ ] sí Extern associar departament encarregat
    
    
    
# Passar faltes

Incidencies:
- Tipus incidencia (Falta, Fata justificada, etc)-> escull el tipus qui posa la falta
- Usuari que la realitza la falta (alumne) (user_id)
- Quan realitza la falta (timeslot) però també el dia que realitza la falta. Per tant un datetimerange
- Usuari que posa la falta (normalment el professor, però també podria ser altres com el tutor o un superadmin). Un user id també
- A quina unitat formativa falta
- Camp notes/observacions
- OCO: A Quina unitat formativa Falta + timeslot és similar al concepte de lliço

Notes:
- Modificació de faltes: sempre guardem l'ultim usuari ha fet la modificació (un tutor pot canviar falta)
-- Audit log permetra llegir els canvis si convé
- Diferències entre la realitat i la programació:
-- Potencialment podriem presuposar pel dia que és quina UF exacté (lliço) correspon la falta. A l'hora de la veritat
les programacions no es poden complir per diferents criteris o els professors canvien les programacions sense canviar-les
"oficialment"
-- Per aquesta raó no tenen pq coincidir les dades de la taula incidencies amb les dades de curriculum. No problem, es fa el
que indiqui el professor al posar la falta

Notes:
- Si falta 3 hores?
-- Una sola incidència?
-- 3 incidències?
- Al final lo important és el temps total que data, per tant guardar un dataihora inici i un dataihora fi

# HORARI

- El horari real hauria de ser canviant cada setmana
- Cert que la majoria tindrem mateix esquema horari desde la perspectiva MPs però desde perpectiva UFS canvia (fins i tot podem canviar d'UF a meitat de setmana)
- També és cert però que hi ha casos que l'horari assignat a una plaça no és el mateix durant tot any (canvia). Més raó per fer l'horari setmanalment

Conclusions:
- No es pot treballar amb timeslots fixes cal utilitzar datetimerange
- Per tant l'horari depen de la setmana/dia que escolli
- Paginador/navegació per setmanes de l'horari

Dades que necessitem per fer l'horari
- Data inici classes
- Data fí
- Dies festius

PERPECTIVA DE L'HORARI
 - Depèn de l'usauri que el mira: Horari de professor, Horari de clase
 - Horari personal alumne: depèn de la matrícula, potser algunes assignatures no les té i per tant no les té a l'horari

# LESSONS

Una lliço és:
- Job/Plaça: plaça que realitza la feina (que tindrà associada un professor titular que serà l'habitual que farà la classe però també pot ser el substitut o usuari actiu que pertoqui en cada moment)
-- Una lliço pot no tenir profe assignat-> Esta en estat potencial, sabem que s'ha de fer però falta assignar professor que la farà
- Timeslot: marge de temps en que es realitza una lliço. Sol ser una hora però pot ser altres duracions?
-- No podem treballar amb timeslots fixes cal utilitzar datetimerange
- A quina Unitat formativa correspon la lliço
- Número de lliço: dubte 33 hores UF són 33 liçons. Lliçons es fan en dos o tres hores seguides o més -> Una sola lliço o divicides en hores
-- Potser poder escollir

# POTENCIAL

- S'hauria de calcular automàticament de forma inicial
- Després podrien fer-se petits canvis sempre evitant/controlant errors (solapaments)

Com fer automàticament
- Data inici
- Començar a asignar hores fins a acabar-les totes
- Que passa si superem la data final -> Que la realitat és una merda! ;-)
- Data final i inici de cada UF tenim una orientativa, utilitzar-la per controlar no passar-se
- Abans de desplegar el automatic proposar dades finalització UFS dins dels rangs vàlids
- Si no caben avisar que faltes hores però simplement les descartem i prou
- La data d'inici i final no serà igual quan hi hagui múltiples grups, s'ha de fer per grup (petis canvis a les dates)
- Desdoblament NO afecta
- Es necessita saber el nombre hores setmanals, de fet els timeslots on volem posar l'assignatura

Potencial de lliçons que s'han de realitzar. Qüestions a tenir en compte:
- En unitats d'hores?
- Tenim una UF amb un nombre hores totals. Per exemple 33
-- Això implica 33 lliçons que s'han de crear
- Altres casos:
-- Desdoblament horari:
--- Desdobla 50% i té 33 hores vol dir que el potencial a cobrir és 33 +11,5: problema utilitzar tan per cents
- Múltiples grups de classe. Per exemples SMXA, SMXB i SMXC:
-- 33*3: 99h de potencial a omplir
  
## Fitxers adjunts

- [ ] Foto proposada pel professor
- [ ] Fotocopia del DNI
    
## Llençol professors


- [ ] Mostrar no llista de professors sinó llista de places
- [ ] Opció extra que indiqui si mostrar professor titular o professor/ substituts 

# Gestió de versions

On mostrar-ho:
- [ ]Footer Welcome Page   
- [ ] Tags? Mostrar versions 
