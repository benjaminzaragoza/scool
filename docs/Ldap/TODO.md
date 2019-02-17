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



## LIST, DATATABLES

Camps a mostrar:
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

