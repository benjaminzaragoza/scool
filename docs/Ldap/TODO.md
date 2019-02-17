# LDAP USERS MODULE

- [ ] Problemes per afegir objectClasses to users: https://github.com/Adldap2/Adldap2/issues/639

## DELETE

- [X] Delete multiple
  - [X] Interfície/frontend preparada
  - [ ] FAlta API/backend

## GET

### BY USER->ID

employeeID -> guardi el id de l'usuari

### BY DNI o altres identificadors

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
## LIST, DATATABLES

Camps a mostrar:
- [X] jpegPhoto
 - [X] getThumbnailEncoded() method a user.
- POSIX INFO
  - [ ] gidNumber
  - [ ] homeDirectory
- Samba info
 - [ ] sambaSID
 - [ ]
EMAILS
 - [ ] carLicense???
 - [ ] email 
 - [ ] highSchoolPersonalEmail
- [ ] EBRE-ESCOOL
  - irisPersonalUniqueid: DNI
  -  highSchoolUserId: 201213-343
  -  highSchoolPersonalEmail: email personal
  -  highSchoolTSI: TSI
- [ ] Mostrar employee type
- [ ] employeeType?
- [ ] employeeNumber
- [ ] Identifier: irisPersonalUniqueid
- [ ] gender
- [ ] Address: postalcode,l, st, homePostalAddress
 - [ ] Mostrar un sol camp i al tooltip les dades separades
- [ ] mobile
TELEFONS:
- [ ] telephoneNumber
- [ ] facsimileTelephoneNumber
ALTRES:
- [ ] Mostrar els timestamps en format hum+a i amb dates formates en l'idioma nostre
  - [ ] Camps calculats per la funció adapt i fet al server side amb PHP
- [ ] Als tooltips mostrar els path complets (amb suffix dc=iesebre,dc=com) però no als camps
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

