# NPM RUN PRODUCTION

- [ ] Recordar executar de tant en tant abans de passar a producció. S'executa npm run production (abans atureu npm run hot) a local abans de fer
un merge amb producció i pujar els canvis. 

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
- [X] Comprovar hnadshake de l'anterior desde lolcahost.
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

Moodle:

- [ ] MoodleUser de entity canviar tots els usos a MoodleUser a App\Modelss
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
  - [ ] moodle_id -> El id de Moodle del teacher (si el té)
  - [ ] moodle_id = null sí no en té
  - [ ] moodle -> Objecte amb la info de l'usuari de Moodle
  - [ ] Testos:
     - [ ] Funció check_teacher ha de comprovar camps moodle_id
- [ ] Que passa si no hi ha mòdul Moodle activat???

HELPERS I SEEDERS
- [ ] Creació de professors associar usuari de Moodle. Taula external_users camp moodle_id
 
Google Apps:
- [ ]

Ldap:
- [ ]

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

# Moodle

Migració:
- [ ] Caldria canviar tots els usuaris actuals de moodle (sense modificar el id) a que utilitzin com a username compte de correu
  - [ ] Quina compte de correu? La institucional
  - [ ] TODO

Millores:
- [ ] Al crear un usuari de Moodle no mostrar els usuaris locals que ja tenen un usuari de Moodle al desplegable
- [ ] Tipus d'usuari:
  - [ ] El pas i secretaries i pares i altres no tenen/ no necessiten usuari de Moodle
- [ ] Treure de Javascript les URLs hardcoded a iesebre.com agafar-les del fitxer de config de config/moodle.php
  
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

FILTRES:
- [ ]Usuaris sincronitzats/Usuaris desincronitzats
Altres:
- [ ] Últim accés tingui un title que mostri la data exacte d'últim accés
- [ ] Importar usuari de Moodle a Local:
  - [ ] Crear usuari utilitzant correu de Moodle i fullname com a name
  - [ ] Crear person a partir de firstname i lastname (autopartir en sn1 i sn2). Altres dades?
- [ ] Al mostrar el uidnumber que sigui un link a la fitxa (show) d'usuari local
- [X] Mostrar el avatar local per poder comparar amb el avatar de Moodle si hi ha uidnumber
- [] Mostrar també dades locals de la persona: givenName, sn1 i sn2 
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

# Permisos

- [X] Usuaris IncidentsManager no poden tancar incidències dels altres usuaris
- [X] Usuaris IncidentsManager no poden afegir usuaris al mòdul Incidències pq no mostra cap usuari el desplegable pq no són
UsersManagers
 - [X] Mostra la llista d'usuaris però no permet afegir rol IncidentsManager (si Incidents)
- [ ] Usuaris IncidentsManager no poden mostrar incidències (apareix blanc el dialeg show)
  - [X] Se soluciona tenint els dos rols IncidentsManager i Incidents
  
# TODOS finals abans posar explotació

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

# BUGS

- [X] Cal crear el canal App.Logs.Loggable.id i arreglar temps real dels logs per a un item
- [ ] No funciona Logout amb user Sergi TUr badenas?
  - [ ] Realment crec que el que passa és que a vegades no mostra correctament la URL /
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
  - [X] Esdeveniment s'ha logat un usuari
  - [X] Esdeveniment un usuari s'ha equivocat al logar-se
  - [X] NO CAL Esdeveniment un usuari s'ha quedat bloquejat al superar el nombre màxim intents
  - [X] Esdeveniment s'ha registrat un usuari
  - [X] Un usuari ha canviat la paraula de pas
  - [X] Impersonate: un admin s'ha impersonat com a i quan surt també
  - [X] Un usuari ha estat verificat (correu electrònic)
Esdeveniments (Illuminate\Auth\Events):
 - [X] Attempting -> NO FER RES DE MOMENT (només quan és un intent erroni):
   - [X] Failed
 - [X] Authenticated | [X] Login són el mateix només un per evitar doble log 
 - [X] Lockout -> NO CAL!!!!
 - [X] Logout
 - [X] PasswordReset
 - [X] Registered
 - [X] Verified 
 - [X] TakeImpersonation is fired when an impersonation is taken.
 - [X] LeaveImpersonation is fired when an impersonation is leaved.
  
# Menu

- [X] TODO -> fer links les entrades de menú amb href i no calgui fer clic!

# ChangeLog Module

## Revisionable vs Custom solution

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

TEMPS REAL:
- [X] La vista quan té activat tremps real hauria d'anar actualitzant (amb Javascript) els valors 1 segons abans o similars.
- [X] Utilitzar vue time ago de Egoist!

Settings:
- [ ] TODO? Duració dels registres i neteja

Backup/Neteja
- [] Permetre netejar registres vells i fer backup dels registres

Performance:
- [ ] Control d'esdeveniment en segon terme (activar i utilitzar queues)

Idees:
- [ ] Base de dades o memòria ràpida tipus Redis?

Vista:
- [X] Utilitzar timeline vuetify
- [X] Mostrar data en que ha succeït el esdeveniment/canvi (tant human com data i temps normals)
- [X] Mostrar missatge del esdeveniment/canvi
- [X] Tipus de registre de canvi: creació/actualització/eliminació
- [X] Mostrar usuari (avatar i nom usuari amb email al title -hover)
- [ ] Esdeveniments no associats a cap usuari? -> No donar error pq usuari pot ser opcional
- [X] Color de l'esdeveniment (nullable)
- [X] Icona (nullable)
- [X] Mòdul de l'esdeveniment -> opcional (nullable a base de daes)
- [ ] Objecte registrable -> Copia persistent de l'estat de l'objecte en aquell moment (camp Json, guardar map() de l'objecte)
- [X] Botó refresh per forçar refresh del registre
- [X] Real Time Logging -> Refresh automàtic (utilitzant Laravel echo i esdeveniments push)
  - [X] Switch que permeti activar/desactivar refresh automàtic
- [ ] Filtres (només quan es crida el component com ChangelogManager o superadmin):
  - [ ] Filtrar per usuari
  - [ ] Filtrar per mòdul
  - [X] Poder accedir al mòdul Registre de Canvis directament a un apartat/filtre -> Per exemples canvis només d'un mòdul
  - [X] Authorizació i filtres: controlar a que pot i que no pot accedir els usuaris
- [X] Search: tipus datatables buscar qualsevol registre

- [X] Utilitzar Data Iterator amb el registre de canvis?
  - [ ] Aconseguir fer funcionar l'animació que funciona sense data iterator però no amb data-iterator: v-slide-x-transition group
  
Testos:
WEB:
- [X] ChangeLogControllerTest:
  - [X] Mostra la vista que correspon amb les dades que pertoquen
  - [] TODO Limitar nombre de dades de la vista
    
API:
- [X] ChangeLogControllerTest
  - [X] Operacions CRUD:
    - [X] List
    - [X] Afegir via API -> No té sentit? sempre anirà associat a un handler/listener d'un esdeveniment
    - [X] Esborrar/Editar -> No tenen sentit!

**IMPORTANT**    
- [ ] JA TENIA MÒDUL????    
  
# Explotació

- [ ] No va https://iesebre.scool.cat/ (sembla utilitza base de dades bàsica i no pas tenant) en canvi https://iesebre.scool.cat/home i altres si
- [ ] Script actualització explotació branca production (STOP npm run hot before)

# Settings

Config és realitza amb el sistema habitual de fitxers de configuració i variables entorn amb valors per defecte.
Algunes settings poden ser "sobreescrites" dinàmicament si l'usuari (manager amb permisos) canvia les settings.
La sobrescritura la fa un ServiceProvider per a cada Mòdul, accedint a una taula Settings (amb keys values).
Com l'accés a base de dades es farà a cada petició utilitzarem Cache
Cada cop es modifiquin les settings cal fer un flush de la cache

SettingsServiceProvider:
- [X] IncidentsServiceProvider: establir els valors de settings de incidencies
- [X] Sistema de settings amb Cache
- [X] Component settings per a mòduls 

# Incidents

Changelog:
- [ ] Filtra extra: Registre de canvis per a un objecte (igual que per User o per a mòdul amb URL propia)
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
- [X] Poder afegir usuaris Manager (IncidentsManager)
   - [X] ES poden indicar dos rols possibles per accedir al menú en comptes de un. Solucionat mostrant només els usuaris amb Rol Incidents
   al desplegable per afegir usuaris IncidentsManager. Cal doncs abans donar rol Incidents per ser IncidentsManager
   - [X] Els IncidentsManager també tenen rol Incidents  

Etiquetes:
- [ ] CRUD etiquetes per als managers

Changelog d'una incidència a la vista Show:
- [ ] Barrejar els comentaris i les accions com fa Github i mostrar missatges intercalats (i ordenats per temps) amb operacions com usuari tal a tancat la incideència
- [ ] Utilitzar vista vuetify timeline per mostrar tant els comentaris com l'historial
- [ ] Comentaris i registre de canvis en temps real a la vista show

Changelog:
- [X] S'ha creat una nova incidència
- [X] S'ha modificat el títol d'una incidència
- [X] S'ha modificat la descripció d'una incidència
- [X] Comentaris
  - [X] S'ha afegit un comentari a una incidència
  - [X] S'ha modificat un comentari a una incidència
  - [X] S'ha esborrat un comentari
- [X] Etiquetes:
  - [X] S'ha assignat una etiqueta a una incidència
  - [X] S'ha tret una etiqueta a una incidència
- [X] Assigness:
  - [X] S'ha assignat un usuari a una incidència
  - [X] S'ha tret una assignat d'una incidència
- [X] S'ha visualitzat una incidència? Funciona parcialment, només quan se visita directament des de un link no si se visita des de la llista datatables
- [X] S'ha obert una incidència  
- [X] S'ha tancat una incidència
- [X] S'ha eliminat una incidència
  
BUGS:
- [ ] OCO changelog a Incidents és una relació que pot provocar Bucle
- [X] L'autenticació de broadcast no funciona amb Impersonation pq el auth user és null -> SOLVED registering Routes inside tenant at web.php routes file
  - [X] Sembla que tampoc va sense impersonation
- [X] Changelog i filtres i temps real
  - [X] Ara mateix filtro correctament al mostrar incidències per mòdul però si està activat temps real el canal escolta TOTES les incidències i mostra altres mòduls
  - [X] Oco amb el botó refresh que sempre refresca tots els logs independentment dels permisos -> TODO API
  - [X] La llista de logs d'un usuari concret no funciona temps real pq no es registra bé el canal privat (dona error 403 to i ser superadmin)
- [X] Treure el botó Afegir (i deixar només afegir i tancar però només amb text Afegir) per als usuaris que no siguin managers.
- [ ] Al visitar: https://iesebre.scool.test/incidents/1 The data content of this event exceeds the allowed maximum (10240 bytes). See http://pusher.com/docs/server_api_guide/server_publishing_events for more info
- [X] Al fer un hover sobre els filtres completades obertes i total s'ha de canviar el cursos a una fletxa per indicar que hi ha una acció possible per filtrar
- [X] Al mostrar la llista incidències total les obertes no es mostra bé la columna tancada (mostra només text per)
- [X] Al eliminar una etiqueta assignada (al ser la primera crec ) s'esborren totes (o potser també la segona). nivell base da des ok, al fer f5 torna a estat tot bé
  - [X] El refresh no actualitza les etiquetes però F5 sí
- [X] No funciona el autocomplete als filtres (creadors i assignees)
- [ ] Els botons afegir comentari i afegir comentari i tancar al estar en loading i disabled desapareixent en comptes 
de mostrar el loading

## Comentaris

- [ ] Canviar la interfície a la nova timeline de vuetify (vegeu exemple advanced):
- [ ] Afegir l'historial i no només els comentaris (registres de canvis per al modle/incidència concret)

- https://vuetifyjs.com/en/components/timelines
- https://github.com/vuetifyjs/vuetifyjs.com/tree/master/src/examples/timelines/advanced.vue

## Idees

- Funcionalitat PING! Com està la incidència? Ara és pot fer amb un nou comentari però com resaltar-lo?

# Wizard config incidències:

Rols i flux de treball:

1) Superadmin activa mòdul incidències (es mostri al menú)
2) Superadmin assigna com a mínim un gestor d'incidències (Rol IncidentsManager)
3) IncidentsManager executa el wizard (es pot executar tant cops com calgui) de configuració Incidències

Wizard Settings:
1) Mòdul actiu (pots desactivar mòdul temporalment pel que sigui?)
2) Assignar usuaris a incidències (assignar Rol Incidents)
4) Altres settings (email de managers, persones a les que es poden assignar incidències, etc)

Usuaris explotació Sergi Tur:
- 1) Superadmin: sergitur@iesebre.com
- 2) Professor: stur@iesebre.com

- [X] Els usuaris no siguin managers incidències no han de poder canviar settings
- [X] Els usuaris no siguin managers incidències no han de poder veure settings????

Un stepper amb els passos:
1) Activar o no mòdul incidències
2) Decidir els usuaris -> assignació roles Incidents i IncidentsManager
3) Altres settings (email de managers, persones a les que es poden assignar incidències, etc)

# ROLS

A settings o similar:
- [X] Gestionar la llista usuaris que tindran el rol Incidents
- [ ] En principi tots els professors
- [ ] Però també hi ha altres com becaris o altres tercers possibles ()
- [X] Gestionar els managers d'incidències (Rol Incidents Manager)

**Filtres**:

- [ ] On sóc mencionat. Depèn implementar mencions (@username)
  - https://laracasts.com/series/whatcha-working-on/episodes/33 
- [X] Buscador -> Full text search field. DE MOMENT NO CAL ES POT BUSCAR PER TOT LO NECESSARI
  - [ ] Permetre buscar per estat oberta/tancada (camp full search amb tots els strings de cerca a actions )
- [X] Mostrar el total d'incidències obertes i tancades
- [X] Permetre veure les incidències per estat (obertes/tancades/totes)
- [X] Per defecte mostrar les incidències obertes
  - [X] IncidentManagers: mostrar totes les incidències obertes
  - [X] Usuaris normals: mostrar també totes les incidències obertes
- [X] Mostrar només les incidències creades per mi. El usuari logat sempre és el primer al desplegable de creadors
- [X] Mostrar les incidències per autor: desplegable amb llista usuaris (Nom i avatar) tenen incidències.
- [X] Mostrar per assignees. 
  - [X] El usuari logat sempre és el primer al desplegable de assignees
- [X] Assignades a mi. Via:
   - [X] El usuari logat sempre és el primer al desplegable de assignees
- [X] Mostrar per labels/tags

**Assignacions**
- [X] Es poden assignar/dessasignar incidències a múltiples usuaris amb el rol Incidents (usuaris d'incidències)
- [X] S'envia correu electrònic
- [X] Mostrar els assignees a la vista show d'una incidència concreta
- [X] Mostrar els assignees als emails 
- [X] Es pot filtrar incidències per assignacions
- [ ] Settings: poder indicar les persones a les que és més habitual assignar incidències
  - [ ] Sortiran les primeres a la llista de possibles assignees
- [X] Només poden assignar incidències els usuaris amb permissos (ara Rol IncidentsManager)

**Etiquetes**
- [X] Mostrar les etiquetes a la vista show d'una incidència concreta
- [X] Es poden assignar i dessasignar etiquetes a les incidències
- [X] Mostrar les etiquetes als emails 
- [X] Es pot filtrar incidències per etiquetes
- [X] Només poden assignar etiquetes els usuaris amb permissos (ara Rol IncidentsManager)
- [X] API Crud etiquetes
- [ ] Interfície web CRUD per a crear etiquetes integrada al desplegable etiquetes

**Tancament incidències**
- [ ] Camp solved_by per saber qui l'ha resolt?
- [X] Camp closed_by per saber qui ha tancat la incidència
- [ ] Mostrar info de tancat per a:
  - [X] Llista incidències (com a title del camp tancada)
  - [X] Al show d'una incidència
  - [X] Als emails


**Notificacions/comunicació**
- [X]
- [X] Establir com un setting configurable el email dels gestors d'incidències
- [ ] Per correu -> TODO
  - Creador de la incidència:
     - [X] Notificació/correu s'ha creat correctament la incidència
     - [X] Rebre notificació cada cop és modifica la incidència
     - [X] Rebre correu cada cop s'afegeix un comentari a la incidència
     - [ ] Mencions?
  - Correu gestors incidències: (maninfo@iesebre.com)
    - [X] Settings: permetre indicar quin és el correu
    - [X] Settings table: key, value, keys poden tenir un prefix per evitar conflictes de noms
    - [X] Enviar email al crear una nova incidència
    - [X] Enviar email quan s'actualitza una incidència
    - [X] Enviar email cada cop hi ha un comentari nou 
    - [X] Enviar email quan es tanca una incidència    
- [ ] TODO: a la app o pàgina HTML (permetre notificacions al navegador) -> Service Workers
- [ ] Com Github tenir un botó que permeti unsubscribe to notifications
- [ ] Telegram?



**Altres**
- [ ] Datatables utilitzar expand per mostrar més info sobre la incidència? Comentaris? Descripció completa?

MENU PRINCIPAL INCIDENCIES:
- [ ] Mostrar un badge que indiqui las incidència noves (des de l'últim login?)

RESPONSIVE:
- [ ] Versió Mobile: Datatables canviar per un Data Iterator de Cards (una incidència un card)

HISTORIAL:
- [X] Historial: especialment de les accions tipus esborrar incidència o comentaris
- [X] https://vuetifyjs.com/en/components/timelines

Ideas taken from Github
- [X] Textareas: http://miaolz123.github.io/vue-markdown/
- [X] https://vuejs.org/v2/examples/
- [X] https://marked.js.org
- [X] Boto extra al afegir comentari: Afegir i tancar la incidència (només per managers)
- [X] Suportar markdown als camps tipus textarea:
  - [ ] Altres extres interessants: @mencions Links HTTP, etc
  - [ ] Poder fer referència+link a un altre incident/issue amb #numissue
- [X] Labels/Tags: els managers poden crear etiquetes per classificar les incidències (un crud d'etiquetes és necessari per posar etiquetes es vulguin)
  - [X] Labels/Tags: tenen nom, descripció, icona i color (es pot fer un preview en directe quan es crea/edita un label)
- [ ] Apartat participants: gent que participa de la discusió/comentaris
- [X] Assignar incidències a usuaris (Assignees)  

# USER RESOURCE vs user map (SOLUCIONAT/OBSOLET)

SOLUCIONAT: No puc utilitzar les dos coses pq aleshores inc codi wet i no tinc Single Source of truth

Antic Fitxer resource he eliminat:

https://github.com/acacha/scool/blob/3121765083986b15adc95e618f62f476fcc73e3c/app/Http/Resources/UserResource.php

Té roles i permissions

Map té més info però no té aquesta concreta (roles i permissions)

Hi ha el UserResource del Tenant i el que no és del Tenant:

https://github.com/acacha/scool/blob/3121765083986b15adc95e618f62f476fcc73e3c/app/Http/Resources/Tenant/UserResource.php

Problema: permissos als menus si mostrar o no mostrar les opcions

Cal revisar component Pare App.vue i app.blade.php i l'ús de la funció checkRoles

# CURRICULUM

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

### CURRICULUM MODULE

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
## Passar faltes

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

== HORARI ==

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

== LESSONS ===

Una lliço és:
- Job/Plaça: plaça que realitza la feina (que tindrà associada un professor titular que serà l'habitual que farà la classe però també pot ser el substitut o usuari actiu que pertoqui en cada moment)
-- Una lliço pot no tenir profe assignat-> Esta en estat potencial, sabem que s'ha de fer però falta assignar professor que la farà
- Timeslot: marge de temps en que es realitza una lliço. Sol ser una hora però pot ser altres duracions?
-- No podem treballar amb timeslots fixes cal utilitzar datetimerange
- A quina Unitat formativa correspon la lliço
- Número de lliço: dubte 33 hores UF són 33 liçons. Lliçons es fan en dos o tres hores seguides o més -> Una sola lliço o divicides en hores
-- Potser poder escollir

== Curriculum ==

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

=== Classrooms ===

- Canvien segons la matrícula. De fet durant la matrícula els alumnes no decideixen grup i sovint no se sap ni els grups que es faran
- Evidentment hi ha una planificació o s'esperà una planificació però per molta matrícula es pot amplica nombre grups o reduir
- Hi ha assignatures com FOL o Anglès que fan classe a múltiples grups al mateix temps
- Al calcular el potencial a omplir cal
- Torn matí o tarda?

=== POTENCIAL ===

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

===

Dades
- Fitxes de professorat de tot el professorat existent? 
- Obtenir les de secretaria per introduir dades
- Obtenir copia o copies per veure el format en que està la fitxa i quines dades es pregunten
- "Cotejar" fitxa de professorat nout amb la fitxa històrica

Substituts:
- Tenen codi de professor propi per impresores? no crec pq quin li dona codi
- Serveix per alguna cosa el codi de profe? O l'únic important és codi de plaça
- Com els gestiono:
  - Tenen un status administratiu especial: substitut (la resta tenen o funcionari (diversos subtipus) o interins)  
  - Cal guardar el codi de professor al que substitueixen?
    - Mateixa plaça (job) la tenen múltiples professors job->user 1an -> camp user_id a job
    - staff -> user_id treure

job-> places de treball
user-> usuari    
staff-> Assignació d'un usuari a una plaça
- Camps: 
  - owner: true/false indicant si és el titular
  - start_date: Per substituts. A la resta null (tot any)
  - final_date: Per substituts. A la resta null (tot any)
  
Fitxers adjunts
================

- Foto proposada pel professor
- Fotocopia del DNI

Càrrecs:
========

Becaris:
- Omplir la taula de posicions amb tots els càrrecs
- A initialize_teachers assignar càrrecs a professors.

Positions table i Position Model

- LLista de càrrecs inclou Tutors, Tutors FCT, Caps de departament

Taula
- name
- shortname: ???
- Roles: rols associats al càrrec
    
Llençol professors
==================

- Mostrar no llista de professors sinó llista de places
- Opció extra que indiqui si mostrar professor titular o professor/ substituts 

Càrrecs


# Gestió de versions

- [X] Mostrar a l'aplicació un apartat per admins que permeti saber la versió de l'aplicació
  - [X] Mostrar el commit de github amb link a Github i data del commit
  - [X] git rev-parse HEAD mostra el hash de la versio
  - [X] hash curt: git rev-parse --short HEAD
   - git show --summary | git log -1
   - git branch mostra les branques
   - git remote show origin
- [X] Cache: cada x minuts
- [X] Boto de flush/refresh de la cache
- [X] Passar la info a javascript al menu meta:     <meta name="git" content="{{ git() }}">
- [X] Crear helper git
- [X] Mostrar la branca actual
- [X] Mostrar commit larg hash actual
- [X] Mostrar commit curt hash actual
- [X] Mostra missatge
- [X] Mostrar data en diferents formats
   
On mostrar-ho:
- [ ]Footer Welcome Page   
- [ ] Toolbar (només per a admins)

Que vull Mostrar:
 - Un link simple que al fer click mostri un dialeg amb més info i també un title on hover
 - Format del link: Hash curt del commit que s'està utilitzant i data del commit en format humà
    - [ ] Exemple: versió: b44f4b6 Fa un minut 
       - [] Title on hover: b44f4b6912ecff88da19f65c456f4620ad750471 15:34:23 20/12/2018 Sergi Tur Badenas
    - Diàleg:
     - b44f4b6912ecff88da19f65c456f4620ad750471 15:34:23 20/12/2018 Sergi Tur Badenas
     - Sigui un link als commits del projecte
     - Link al repositori Github (nom curt tipus acacha/scool amb link)
     - Tags i commit ?  
