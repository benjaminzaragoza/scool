
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

BUGS:
- [ ] No funciona el autocomplete als filtres (creadors i assignees)

Idees:
- Funcionalitat PING! Com està la incidència? Ara és pot fer amb un nou comentari però com resaltar-lo?

# ROLS

A settings o similar:
- [ ] Gestionar la llista usuaris que tinfran el rol Incidents
- [ ] En principi tots els professors
- [ ] Però també hi ha altres com becaris o altres tercers possibles ()
- [ ] Gestionar els managers d'incidències (Rol Incidents Manager)

**Filtres**:

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
  - [ ] El usuari logat sempre és el primer al desplegable de assignees
- [ ] Assignades a mi. 
- [ ] On sóc mencionat. Depèn implementar mencions (@username)
- [ ] Mostrar per labels/tags

**Assignacions**
- [X] Es poden assignar/dessasignar incidències a múltiples usuaris amb el rol Incidents (usuaris d'incidències)
- [X] S'envia correu electrònic
- [ ] Mostrar els assignees a la vista show d'una incidència concreta
- [ ] Mostrar els assignees als emails 
- [X] Es pot filtrar incidències per assignacions
- [ ] Settings: poder indicar les persones a les que és més habitual assignar incidències
  - Sortiran les primeres a la llista de possibles assignees
- [X] Només poden assignar incidències els usuaris amb permissos (ara Rol IncidentsManager)

**Etiquetes**
- [ ] Mostrar les etiquetes a la vista show d'una incidència concreta
- [ ] Mostrar les etiquetes als emails 
- [ ] Es pot filtrar incidències per etiquetes
- [X] Només poden assignar etiquetes els usuaris amb permissos (ara Rol IncidentsManager)

**Tancament incidències**
- [ ] Camp closed_by per saber qui ha tancat la incidència
- [ ] Camp solved_by per saber qui l'ha resolt?

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
- [ ] TODO: a la app o pàgina HTML (permetre notificacions al navegador)
- [ ] Com Github tenir un botó que permeti unsubscribe to notifications
- [ ] Telegram?



**Altres**
- [ ] Datatables utilitzar expand per mostrar més info sobre la incidència? Comentaris? Descripció completa?

MENU PRINCIPAL INCIDENCIES:
- [ ] Mostrar un badge que indiqui las incidència noves (des de l'últim login?)

RESPONSIVE:
- [ ] Versió Mobile: Datatables canviar per un Data Iterator de Cards (una incidència un card)

HISTORIAL:
- [ ] Historial: especialment de les accions tipus esborrar incidència o comentaris
- [ ] https://vuetifyjs.com/en/components/timelines

Ideas taken from Github
- [X] Textareas: http://miaolz123.github.io/vue-markdown/
- [X] https://vuejs.org/v2/examples/
- [X] https://marked.js.org
- [X] Boto extra al afegir comentari: Afegir i tancar la incidència (només per managers)
- [X] Suportar markdown als camps tipus textarea:
  - [ ] Altres extres interessants: @mencions Links HTTP, etc
  - [ ] Poder fer referència+link a un altre incident/issue amb #numissue
- [ ] Labels/Tags: els managers poden crear etiquetes per classificar les incidències (un crud d'etiquetes és necessari per posar etiquetes es vulguin)
  - [ ] Labels/Tags: tenen nom, descripció i color (es pot fer un preview en directe quan es crea/edita un label)
- [ ] Apartat participants: gent que participa de la discusió/comentaris
- [ ] Assignar incidències a usuaris (Assignees)  

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

# USER RESOURCE vs user map

No puc utilitzar les dos coses pq aleshores inc codi wet i no tinc Single Source of truth

Antic Fitxer resource he eliminat:

https://github.com/acacha/scool/blob/3121765083986b15adc95e618f62f476fcc73e3c/app/Http/Resources/UserResource.php

Té roles i permissions

Map té més info però no té aquesta concreta (roles i permissions)

Hi ha el UserResource del Tenant i el que no és del Tenant:

https://github.com/acacha/scool/blob/3121765083986b15adc95e618f62f476fcc73e3c/app/Http/Resources/Tenant/UserResource.php

Problema: permissos als menus si mostrar o no mostrar les opcions

Cal revisar component Pare App.vue i app.blade.php i l'ús de la funció checkRoles

CURRICULUM

== Passar faltes ==

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
