# Correu electrònics Inbound
- https://www.mailgun.com/inbound-routing
- https://docs.beyondco.de/laravel-mailbox/1.0/drivers/drivers.html#sendgrid

Idea:
- [ ] Permetre crear incidències enviant un email
- [ ] Només els usuaris registrats i amb permisos (Incidents rol i email coincideixi amb el d'un usuari) podran enviar emails
- [ ] Un cop enviat email es rep un email de confirmació, fins que no es confirma no es crea la incidència
- [ ] Subject serà el del email i la descripció el contingut

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

# Mòdul de comandes

- [ ] Associat a incidències (incidències que queden pendents d'una comanda)
- [ ] Comanda oberta/tancada
- [ ] Rebre un email cada cop hi ha una nova comanda
- [ ] Cal validació de comandes abans enviar administradora?

# Users management

Troubleshooting:
- [X] Botó afegir email corporatiu: executa un search a la api. Si previàment hi ha cache el search és ràpid sinó 
tarda molt en obrir-se
  - [X] No hi ha cap indicador que estigui treballant -> sembla que no funcioni

Dades personals
- [ ] Card/formulari creació/modificació de dades personals d'un usuari concret
  - [ ] No incloura dades específiques segons tipus usuari
    -  [ ] Formulari dades de professor  

EDIT:
- [X] Editar el name inline a la llista d'usuaris (datatables). NO inline però si currat per poder mdificar sn1 sn2 i givenName també
- [X]  Editar l'email name inline a la llsita d'usuaris (datatables). Fet no inline però fet
  - [X] El email ha de passar a no confirmat -> S'hauria de tornar a enviar el email
- [X] Modificar el mòbil inline  
- [ ] Modificar el tipus usuari

Changelog:
- [ ] Igual que incidències logar totes les accions

USER DELETE:
- [ ] Preguntar si també es vol eliminar usuari de Google, usuari de Ldap i usuari de Moodle
- [X] Dona un error que no troba l'usuari local si s'intenta esborrar un usuari de Google del qual ja s'ha esborrar l'usuari
- [X] CONTINUA DONANT ERROR Dona un error que no troba l'usuari local si s'intenta esborrar un usuari de Google del qual ja s'ha esborrar l'usuari
- [ ] Proteccions contra errors: usuaris protegits no es puguin esborrar    

USER ADD WIZARD
- [X] Crear usuari de Moodle
  - [X] El nou usuari de Moodle ha de tenir el idnumber igual a l'usuari local del sistema
- [ ] Tema password i sincronització dels diferents password
  - [ ] Moodle no permet passar hash del password -> S'ha de canviar password de Moodle en el moment es canvia password local
  - [ ] Com gestionar-lo?
  - [ ] Lligar amb el dialeg pendent que permetra canviar paraules de pas
- [ ] Eliminar tots els links i valors hardcoded de Javascript apuntant a iesebre.com i agafar-los del tenant
- [ ] Estils:
  - [ ] Botons rojos no!
  - [ ] Aplicar jerarquia! Quina botons són més important i quins secundaris
- [X] Fase/Step assignar rols
  - [X] Igual que la opció de modificar rols d'un usuari a la llista però sense dialeg (incrustat)
- [X] Primer camp tipus usuari
  -  Segons tipus usuari ajudarem/assistirem en la creació. Exemples:
    - [X] Crear usuari de Moodle: el personal com conserges i secretaria no necessiten usuari de Moodle
    - [X] Assignar rol: Hi ha rols predefinits per cada tipus usuari. Assignar rol només serveix per assignar rols extres els predefinits ja estaran assignats 
- [X] Refrescar la llista d'usuari cada cop que es crea un nou user
- [X] Refrescar la llista d'usuari cada cop que es crea un nou usuari Google
- [X] Refrescar la llista d'usuari cada cop que es modifica un avatar


AVATARS:
- [X] NO es refresquen si ja estaven cachejats
  - [X] NO FER! Mirar solució de tasques i posar headers HTML per no fer cache de les imatges -> RENDIMENT POBRE
  - [X] Afegir un hash a la URL del avatar que depengui del contingut de la imatge i així evitar cache


Operacions massives:
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
  
Operacions:
- [ ] Check individual d'un usuari
- [ ] Diàleg canviar paraula de pas
  - [ ] Poder posar una paraula de pas
  - [ ] Generador de paraules de pas i enviar per correu electrònic
  - [ ] Forçar canvi de paraula de pas al següent login  
   
RELACIONS AMB ALTRES ENTITATS/MODELS

ROLS:
- [X] Dialeg que permeti gestionar rols d'un usuaris concret (afegir i treure rols)

PERSONES:
- Nom:
  - [X] Edició inline del nom
    - [X] Un dialeg flotant que permeti canviar sn1, sn2, givenName i recalculi nom usuari automàticament
USUARI MOODLE
- [ ] Operació sync
  - [ ] Actualitzi també dades personals Moodle? (adreces etc)
  - [ ] Al crear usuari a partir usuari local si estas dades estan disponibles crear-les
- [ ] Fer quelcom similar a Usuari Google poden canviar l'usuari associat, dessasociar o associar i sincronitzar dades
  - [X] Mostrar l'usuari de moodle associat
  - [X] Editar/canviar l'usuari de Moodle associat
  - [ ] Sincronitzar l'usuari de Moodle associat
  - [X] Dessasociar l'usuari de Moodle associat
  - [X] Implementar l'opció cache a l'api de llista usuaris Moodle com a Google Users
  - [X] Associar Usuaris Moodle
    - [X] Al associar un usuari s'hauria de modificar el idnumber de Moodle i posar el id del usuari local associat
  - [ ] Afegir a la relació el fullname i el email de Moodle
  - [X] dessasociar usuari de Moodle
    - [X] Al desassociar un usuari s'hauria de modificar el idnumber de Moodle i posarlo a null
  - [X] Usuaris de Moodle ha de tenir un link directe a crear un nou usuari de Moodle
    - [X] Link directe a crear un usuari de Moodle ja indiqui l'usuari local associat per omplir més ràpid el formulari
USUARI DE GOOGLE
- [X] Link directe a crear un usuari de Moodle ja indiqui l'usuari local associat per omplir més ràpid el formulari
- [X] Mostrar acció permeti navegar (link) a l'usuari de Google associat
  - [X] Show usuaris Google existeix? Crear
  - [X] FILTRE: Mostrar només (o disabled sinó) els usuaris no tinguin camp user google associat
- [X] CorporativeEmail
  - [X] Falta o no funciona operació Afegir usuari corporatiu
    - [X] És correcte. Hi ha un 404 d'una crida API: https://iesebre.scool.test/api/v1/gsuite/users/search 404
    - [X] Funcionalitat ajudar a buscar posible usuari Google associat
  - [X] Dessasociar usuari Google a usuari
  - [X] Sincronitzar
  - [X] Editar -> Canviar l'usuari corporatiu associat

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

FILTRES
- [ ] Filtres usuaris
  - [X] Per típus/s d'usuari
  - [X] Per Rol/s
    - [ ] No mostrar els usuaris admin al filtrar per Roles (tenen tots els rols i permisos)
  - [ ] Altres característiques/filtres:
     - [X] Emails confirmats/verificats (email)
     - [ ] Mòbils no confirmats/verificats. TODO falta està funcionalitat
     - [X] Sense email corporatiu
     - [ ] Sense avatar. TODO -> Falta crear camp (calculat) indiqui usuari no té avatar 
     - [X] Mai logats al sistema
     - [ ] Logats desde un periode especific
- [x] Rendiment: Masses queries 385. aRREGLAT AMB eAGER lOADING
- [ ] Mobile: de moment camp no obligatori però després podria servir com alternativa al email.
- [ ] Usuaris no tenen email poder utilitzar el mòbil i SMS per a fer autenticació?
- [X] Last Login de l'usuari, permetre saber usuaris no s'han logat mai. FET UN FILTRE
- [X] Esborrat massiu d'usuaris
  - [ ] Protegir alguns usuaris -> no es puguin esborrar:
    - [ ] Superadmins
    - [ ] Configuració: altres usuaris no es puguin esborrar    

# Usuaris acabats de registrar | sense rols

## Dashboard

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

## Exalumne

- Per aquells que els interessi mantenir contacte amb el centre
- Recuperar usuari si havien tingut? Com fer?
- Indicar les xarxes socials a les que es pot apuntar per estar al dia de les novetats del centre 

## Alumne

Si l'usuari vol ser alumne ha de formalitzar una matrícula:
- Periode de matrícula: cal fer un manteniment al mòdul matrícula que permeti indicar periode de matrícula
- Mantenir-me informat|Notificar/Avís quan s'obri el periode de matrícula/preinscripció
  - Indicar els estudis en els que el potencial alumne està interessat -> llista
  - Agafar alguna dada extra com el telèfon Mòbil -> Potser també l'adreça per fer mailing?
  - Indicar les xarxes socials a les que es pot apuntar per estar al dia de les novetats del centre 
- Sí es periode de matrícula i està activada la automatriculació pot fer la matrícua
- Preinscripció?

## Professor 
- Usuari potencial professor
## Ex-profesor
- Posibilitar mantenir compte de centre com correu

## Personal

## Superusuaris
- Usuaris admin amb tots els permisos


# Positions

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

# CSS VUETIFY i FONTS
- [ ] Estan instal·lats com a CDN externs. Instal·lació local: https://vuetifyjs.com/en/getting-started/quick-start
- [ ] A més vuetify està agafat de unpkg que a vegades falla
- [ ] També moure fonts de Icones de Material i de Fonw awesome 

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

#### Moodle

- [ ] MoodleUser de entity canviar tots els usos a MoodleUser a App\Models
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

Relació amb USERS:
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
- Operacions massives:
  - [ ] Eliminació
- Filtres:
  - [ ] Sense usuari local
  - [ ] Amb usuari local
  - [ ] No sincronitzat
  - [ ] Confirmat
  - [ ] No confirmat
  - [ ] No ha entrat mai
- [ ] Color de la toolbar i dense
- [ ] estil de la taula més dens?
- [ ] Accions posar en components externs

Millores:
- [ ] Al crear un usuari de Moodle no mostrar els usuaris locals que ja tenen un usuari de Moodle al desplegable
- [ ] Tipus d'usuari:
  - [ ] El pas i secretaries i pares i altres no tenen/no necessiten usuari de Moodle
- [ ] Treure de Javascript les URLs hardcoded a iesebre.com agafar-les del fitxer de config de config/moodle.php

Migració:
- [ ] Caldria canviar tots els usuaris actuals de moodle (sense modificar el id) a que utilitzin com a username compte de correu
  - [ ] Quina compte de correu? La institucional
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
