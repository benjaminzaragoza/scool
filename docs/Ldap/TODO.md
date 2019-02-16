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

