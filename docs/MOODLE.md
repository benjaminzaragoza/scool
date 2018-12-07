# 0 . Assistent per activar control per sistema extern de Moodle

El trobareu a:

```
https://www.iesebre.com/moodle/admin/settings.php?section=webservicesoverview 
```

Aquí cal:
- Habilitar els serveis web
- Habilitar el protocol REST 

# Fer l'usuari extern administrador:

A:

```
https://www.iesebre.com/moodle/admin/roles/admins.php
```

Podem establir l'usuari com administrador

# I. Create a new role for web services | Crear un nou rol per als webservices 

Aneu a:

```
Site administrator > Users > Permissions > Define roles
Administració del lloc > Usuaris > Permisos -> Definició de rols
https://www.iesebre.com/moodle/admin/roles/manage.php
```

Feu clic a:

```
Afegeix un nou rol
```

Feu clic a continua al primer formulari (per saltar-lo) i ara si podem crear el rol. Poseu les seguents dades:

- Nom curt: scool
- Nom complet personalitzat: scool
- Descripció personalitzada: scool
- Tipus de context en què es pot assignar aquest rol: Sistema
- Capacitats: com a mínim a de tenir la capacitat: Crear usuaris


Un cop creat el rol queda de la següent manera:

```
https://www.iesebre.com/moodle/admin/roles/define.php?action=edit&roleid=9
```

```
Site administrator > Users > Permissions > Assign system roles
Administració del lloc > Usuaris > Permisos -> Assigna rols globals
https://www.iesebre.com/moodle/admin/roles/assign.php?contextid=1
```

## II. Assign a user to the new role | Assignació del rol a l'usuari

Ara creem un usuari:

```
Site administrator > Users > Permissions > Assign system roles.
```

Escolliu el rol i afegiu l'usuari

# III. Add user to authorized users

This is only required if you checked the Only authorized users option when you created your external service.

Go to Site administrator > Plugins > Web services > External services.
Click on Authorized users link on your web service.
Add the user to the list of Authorized users.
Check the warning under the form. If your user doesn't have some capabilities, Moodle will signal that as a warning under the form. You can fix that by adding the missing capabilities to the role that we created earlier.

# IV. Create a token
Now we can go to the token manager and generate a new token to our user for our external web service.

BS: Don't forget to enable web services first. Enable REST protocol or whatever protocol you are willing to use.

El token es pot veure a:
https://www.iesebre.com/moodle/admin/settings.php?section=webservicetokens

# V Configuració environment Laravel

Al fitxer .env guardem les dades confidencials del mòdul Moodle:

```
MOODLE_USER=scool
MOODLE_PASSWORD=PASSWORD_HERE
```

# VI Dades de l'usuari a Moodle

A:
 
```
https://www.iesebre.com/moodle/admin/user.php
```
Podeu buscar l'usuari scool i veure les seves dades a:

https://www.iesebre.com/moodle/user/profile.php?id=5618

- Correu electrònic associat sergitur@iesebre.com

# Recursos
- https://stackoverflow.com/questions/47688746/create-user-in-moodle-through-web-services-php
- He creat rol scool: https://www.iesebre.com/moodle/admin/roles/define.php?action=view&roleid=9
- https://www.iesebre.com/moodle/admin/settings.php?section=webservicesoverview
