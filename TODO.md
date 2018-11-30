# Moodle

Bugs:
- [X] Reduir les 506 queries amb Eager Loading

Controladors web
- [X] Controlador web i Test
- [X] Tots els permisos i rols comprovats als tests i creats a helpers.php
- [X] Afegida opció de menú a la taula menus
- [ ] Afegir mòdul
- [ ] Tots els testos marcats com group moodle i slow per no executar-los sempre
Controlador API:
- [X] Refresh/index
- [X] Remove
- [ ] Add/store

Usernames:
- [ ] Càlcul centralitzat del username?
- [ ] On guardar el username -> base de dades camp únic?
- [ ] no preguntar mai al usuari -> calcular
- [ ] Assignar durant la creació del registre usuari (al registrar o crear l'usuari de qalsevol altre manerta)
- [ ] Es fa una proposta de nom usuari però es comprova si algú ja la té
- [ ] Moodle -> usuaris com emails
  - [ ] https://www.iesebre.com/moodle/admin/settings.php?section=sitepolicies
  - [ ] Permet caràcters estesos en els noms d'usuari


MOODLE password recovery:
- Usuaris ldap -> no tenen password el busquen a Ldap però també estan a la base de dades (amb passwrd buit)
- El webservice només deixa posar password a partir de text en clar no podem sincronitzar hashes
- Webservice si té una opció per crear la paraula de pass i enviar per email

Altres:
- https://stackoverflow.com/questions/47688746/create-user-in-moodle-through-web-services-php
- He creat rol scool: https://www.iesebre.com/moodle/admin/roles/define.php?action=view&roleid=9
- https://www.iesebre.com/moodle/admin/settings.php?section=webservicesoverview
- [ ] Treure de Javascript les URLs hardcoded a iesebre.com agafar-les del fitxer de config de config/moodle.php

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

# Gestió de versions

- [ ] Mostrar a l'aplicació un apartat per admins que permeti saber la versió de l'aplicació
  - [ ] Mostrar a tots els usuaris la versió
  - [ ] MOstrar el commit de github amb link a Github i data del commit

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
