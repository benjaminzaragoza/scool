# LDAP USERS MODULE

- [ ] Problemes per afegir objectClasses to users: https://github.com/Adldap2/Adldap2/issues/639

## Altres

- Poder canviar el base DN (ara està all però poder veure altres anys?)

## REFRESH

- [ ] El refresh per api refresqui tots els usuaris (força forget de cache)

## FILTRES i altres

- [ ] Mostrar usuari Linux
- [ ] Mostrar usuari Windows
- [ ] Los dos tipus anteriors
- [ ] Ni Linux ni Windows
- [ ] Opció mostrar camps ebre-escool (highschooluserid i email i similars)
  - [ ] No marcada per defecte (al futur no caldrà)

- Filtrar per baseDN -> Un arbre
 - ou=All,dc=iesebre,dc=com
   - subcarpetes?
- Aconseguit tots els baseDN que existeixen -> Guardar-los a una estructura tipus arbre
- [ ] Una opció inicial més senzilla: no cal sigui arbre simplement array amb tots els basedn

## INLINE

- Canviar nom (sn1, sn2 i givenName)
- uidnumber si fos incorrecte o repetit?
- Canviar grup Windows (permetria indicar administradors de domini?)
- Canviar la home
- Emails?

## CAMPS:

- [ ] Passwords sincronitzades?
  - [ ] Comparara hash md5
  - [ ] a ebre-escool hi ha un lm calculated i es compara amb real per saber si està sync
  - [ ] Permet saber si els windows passwords estàn ok

## ACCIONS


- [ ] Sincronitzar amb usuari local
  - [ ] JpegPhoto
  - [ ] Altres dades: sn1, sn2, givenName
  - [ ] Emails???
  - [ ] Password no es pot!!! Cal preguntar/establir un nou password per sincronitzar???
  - [ ] uid no es pot sincronitzar (és el que determina la relació user local i user Ldap)
- [ ] Check/Filtres
  - [ ] NO hi ha uids repetits
  - [ ] No hi ha sambasids repetits
  - [ ] No hi ha ningú amb un sambasid prefix no correcte
  - [ ] No hi ha ningú sense uidnumber
  - [ ] No hi ha ningú sense uid
  - [ ] No hi ha ningú sense gidnumber (roo gidnumber 0 | nobody: 65534)
- [X] Canviar paraula de pas
- [ ] Suspendre usuari (acctFlags)
- [ ] Temes canvi password van? Fer alguna prova
- [ ] Esborrar
- [ ] Veure tots els camps
- [ ] Verificar paraula de pas:
  - [ ] S'escriu la paraula de pas i es compara amb els hashes:
    - [ ] userPassword (Linux)
    - [ ] lmpassword i ntpassword

## ldap_users table an Model:

- [X] Similar al que fem amb Moodle_users i google_users
- [X] Crear migració. Camps
  - id, user_id, cn?
- [X] Permet relacionar local user amb usuari Ldap  
- [ ] Falta fer proves com funciona un cop es puguin crear usuaris Ldap

# Change password

- [ ] Mostrar informació només canvia usuari Ldap i posar link a canviar usuari local
- [ ] validació mínima 6 caràcters 
- [ ] Ara es fa amb MD5 -> Poder indicar un altre tipus (SHA1). Config? o al formulari

## DELETE

- [X] Delete multiple
  - [X] Interfície/frontend preparada
  - [ ] Falta API/backend

## GET

- [ ] BY USER->ID employeeID -> guardi el id de l'usuari
- [ ] BY DNI o altres identificadors
- [X] By email
- [X] By uid

## CREATE

A partir d'un usuari local; $user

TRES FASES:
- [ ] Primera: a partir d'un user simple (no té dades personals ni dades de rols)
  - [ ] Info disponible: tipus usuari, Nom, sn1, sn2, username?, email personal, tlf 
  - [ ] Email corporatiu?
  - [ ] Que cal crear a ldap
    - [ ] cn: Sergi Tur Badenas
    - [ ] gidNumber: 513
    - [ ] highscooluserId is mandatory però amb quin esquema? Cal?
    - [ ] homeDirectory: /home/stur
    - [ ] sambaSID: S-1-5-21-4045161930-1404234508-1517741366- AFEGIR RELATIVE NUMBER -> Evitar duplicats
       - [ ] sambaAcctFlags: [UX    ]
       - [ ] sambaBadPasswordCount: 0
       - [ ] sambaBadPasswordTime: 0
       - [ ] sambaDomainName: INSEBRE
       - [ ] sambaHomeDrive: U
       - [ ] sambaHomePath: \\samba02\stur
       - [ ] sambaLMPassword
       - [ ] sambaNTPassword
       - [ ] sambaPasswordHistory: 000000000000000000000000000000
       - [ ] sambaPrimaryGroupSID: S-1-5-21-4045161930-1404234508-1517741366-5761
       - [ ] sambaLogoffTime: ????
       - [ ] sambaLogonScript: professorat.bat
       - [ ] sambaLogonTime: 0
       - [ ] sambaMungedDial: ???
       - [ ] sambaMungedDial: ???
       - [ ] sambaMungedDial: ???
       - [ ] 
    - [ ] sn: Tur Badenas
    - [ ] uid: stur
    - [ ] uidnumber: 
    - [ ] employeeNumber: $user->id 
    - [ ] employeeType: profe o millor el id? $user->type_id
    - [ ] givenName: Sergi
    - [ ] sn1: Tur
    - [ ] sn2: Badenas
    - [ ] loginshell: /bin/bash
    - [ ] mobile: mobil si s'ha proporcionat
    
    - [ ] o i ou ?
    - [ ] userPassword ????
FOTO LDAP
    - [ ] Segona fase, segon step del wizard creació usuaris i opcional    
    - [ ] jpegPhoto

## UIDS i GIDNUMBERS

http://acacha.org/mediawiki/Proposta_de_gesti%C3%B3_del_uid_i_gid_numbers#.XGp3N7r0k5k    

Grups per a Samba:

- 512: domadmin. Els usuaris d'aquest grup són administradors de domini de Samba. Lo important és el gidnumber no el nom que se li doni (el nom podria contenir espais i dir-se Domain Admin o quelcom similar però aleshores dona problemes a aplicacions com Gosa al gestionar els grups)
- 513: Usuaris de domini. Nota: No tic clar que sigui imprescindible que els usuaris siguin d'aquest grup.
- 514: DomainGuests
- 515: DomainComputers. El grup de les comptes de màquina de domini Samba. És el grup primari (i normalment l'únic grup) dels comptes de màquina, per això si mireu l'objecte Ldap del grup semblarà que no hi ha cap membre del grup, i no és així ja que els grups primaris no apareixen (formen part de la propia compte d'usuari i no pas del grup)
- 548: domaccountoperators ???
- 550: printoperators. Important per a la gestió d'impressores per xarxa (vegeu Cups)
- 551: dombackupoperators ???
- 552: replicators ??
    
## LIST, DATATABLES

O i OU i components:

```
$result = App\Models\LdapUser::findByUid('stur')
$result->getDnBuilder();
Adldap\Models\Attributes\DistinguishedName {#4520}
$result->getDnBuilder()->getComponents();
=> [
     "cn" => [
       "Sergi Tur Badenas",
     ],
     "uid" => [],
     "ou" => [
       "people",
       "Informatica",
       "Profes",
       "All",
     ],
     "dc" => [
       "iesebre",
       "com",
     ],
     "o" => [],
   ]
```

Camps a mostrar:
- [ ] ObjectClasses
 - [] Acció que mostri totes les object classes
   - [ ] Poder filtrar per objectClasses    
 - [ ] En un sol camp mostrar el tipus/tipus d'usuaris
   - [ ] Poder filtrar com si fossin tags
 - [ ] Usuaris Linux (tenen posixAccount)
    - [ ] Icona linux
 - [ ] Usuaris Windows (tenent sambaSamAccount)
   - [ ] Icona Windows
 - [ ] Usuaris ebre-escool: highSchoolUser
- [X] jpegPhoto
 - [X] getThumbnailEncoded() method a user.
- POSIX INFO
  - [ ] gidNumber: 
    - [ ] 512: Domain admin
    - [ ] 513: Usuari de domini
    - [ ] Nobody?
  - [ ] homeDirectory
- Samba info
 - [ ] sambaSID
 - [ ] gidNumber: 513
EMAILS
 - [ ] carLicense???
 - [X] email 
 - [ ] highSchoolPersonalEmail
- [X] EBRE-ESCOOL
  - [X] irisPersonalUniqueid: DNI
  - [X] highSchoolUserId: 201213-343
  - [X] highSchoolPersonalEmail: email personal
  - [ ] highSchoolTSI: TSI
- [X] Mostrar employee type
- [X] employeeType?
- [X] employeeNumber
DADES PERSONALS CAL?
- [ ] Identifier: irisPersonalUniqueid
- [ ] gender
- [ ] Address: postalcode,l, st, homePostalAddress
 - [ ] Mostrar un sol camp i al tooltip les dades separades
- [ ] mobile
TELEFONS:
- [ ] telephoneNumber
- [ ] facsimileTelephoneNumber
ALTRES:
- [X] Mostrar els timestamps en format hum+a i amb dates formates en l'idioma nostre
  - [X] Camps calculats per la funció adapt i fet al server side amb PHP
- [X] Als tooltips mostrar els path complets (amb suffix dc=iesebre,dc=com) però no als camps
- [X] Mostrar sn1, sn2 i givenName al tooltip del CN
- [ ] Tipus de password (password hash)

ACCIONS
- [ ] Comprovar la paraula de pas (es pregunta a l'usuari per una paraula de pas, es fa el tipus hash pertoqui i es compara si és ok)

# TROUBLESHOOTING

## Can't contact LDAP server

Faltaven ok dades de contacte

LDAP_HOSTS="192.168.50.30"
LDAP_PORT=389
LDAP_BASE_DN="ou=All,dc=iesebre,dc=com"
LDAP_USERNAME="cn=admin,dc=iesebre,dc=com"
LDAP_PASSWORD=PASSWORD HERE

## LDAP no mostra cap usuari

Si:

```
Adldap::search()->users()->get()
```

No mostra res aleshores cal indicar l'esquema correcte (a config/ldap.php), en el nostre cas:

```
'schema' => Adldap\Schemas\OpenLDAP::class,
```

