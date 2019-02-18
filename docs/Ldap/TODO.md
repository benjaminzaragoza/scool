# LDAP USERS MODULE

- [ ] Problemes per afegir objectClasses to users: https://github.com/Adldap2/Adldap2/issues/639

## DELETE

- [X] Delete multiple
  - [X] Interfície/frontend preparada
  - [ ] FAlta API/backend

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
 - [ ] Usuaris Linux (tenen posixAccount)
 - [ ] Usuaris Windows (tenent sambaSamAccount)
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
- [ ] Comprovar la paraula de pas (es pregunta a l'usuri per una paraula de pas, es fa el tipus hash pertoqui i es compara si és ok)

# Can't contact LDAP server

Faltaven ok dades de contacte

LDAP_HOSTS="192.168.50.30"
LDAP_PORT=389
LDAP_BASE_DN="ou=All,dc=iesebre,dc=com"
LDAP_USERNAME="cn=admin,dc=iesebre,dc=com"
LDAP_PASSWORD=PASSWORD HERE

# LDAP no mostra cap usuari

Si:

```
Adldap::search()->users()->get()
```

No mostra res aleshores cal indicar l'esquema correcte (a config/ldap.php), en el nostre cas:

```
'schema' => Adldap\Schemas\OpenLDAP::class,
```

