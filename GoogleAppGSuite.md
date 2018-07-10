# Google Apps Watch/Push Notifications

Google api limits to 6 hours the watch expiration time. Also permits to ahve duplicate watches (so we can receive multiple
times the same notification throuh different channels)

Every channel has:
- Id: we use UUId with $channel->setId($uuid = Uuid::generate()->string);
- Token: $token -> $channel->setToken($token = str_random(20));

We use a table to register every time a watch is executed:

table: google_watches | model: GoogleWatch
- id
- channel_id
- token
- channel_type: add: user created event | delete: user deleted event | makeAdmin: user admin status change event
                undelete: user undeleted event | update: user updated event
- expiration_time -> Used as boolean confirmed (if exist then we have received a sync)
- updated-at
- created-at

Then we use Laravel Scheduler to execute watches every 5 hours (before expiration in 6 hours). When a watch is executed
then we confirm wath is received by Google looking for confirmed boolean at table google_watches (oly filled to true if
google sync message is received) at:
- 30 seconds later
- 1 minute later
- 5 minutes later

If not sync is received we execute watch again.

## Google Apps Watch/Push Notifications ALERTS

- Execute every hour a command than checks exists a valid watcher: At google_watches table look for an active channel (not expired)


# Link:

http://admin.google.com

# Docs wiki:

http://acacha.org/mediawiki/Programaci%C3%B3_Google_Apps#.Wvv013XFKkA

# Passos a seguir:

Passos que cal realitzar com administrador per activar accés per API:

http://acacha.org/mediawiki/Programaci%C3%B3_Google_Apps

# CLOUD IDENTITY & ACCESS MANAGEMENT (IAM)

Cal obtenir un fitxer Json (abans eren fitxer P12) amb les dades per accedir al domini.

Per això cal entrar amb usuari administrador de GSuite a:

https://console.developers.google.com/iam-admin/

Crear un projecte i un service account amb privilegis

# APIS

## DIRECTORY

https://developers.google.com/admin-sdk/directory/

# ERRORS I SOLUCIONS

La api va donant missatges d'error en format Json

## Login required

Cal iniciar la sessió amb les següents dades a environment:

GOOGLE_SERVICE_ENABLED=true
GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION=./scool-07eed0b50a6f.json

## INVALID SCOPE. Empty or missing scope not allowed.

A config/google.php cal definir els scopes:

    'scopes'          => [ 'https://www.googleapis.com/auth/admin.directory.user' ],
    
En aquest cas hem posat el de directory. Consulteu més scopes a:

https://developers.google.com/identity/protocols/googlescopes

## Client is unauthorized to retrieve access tokens using this method

Un cop creada la compte de tipus Service Account cal a més assegurar-se de:

- Té activada la opció domain Wide Delegation-
- Cal anar al panell d'administració del domini admin.google.com i anar a Security/Configuracion Avanzada/Autenticació/Administrar el accesso de cliente APi
- Aquí cal afegir el client posant el id (el mateix del fitxer env)
https://developers.google.com/identity/protocols/OAuth2ServiceAccount#delegatingauthority
- Ambitos són scopes cal posar: https://www.googleapis.com/auth/admin.directory.user 


## Dades necessàries per a un tenant concret:

- Fitxer json amb la clau sel service account

## Passwords

Al crear un usuari cal especificar paraula de pas. 

- Problema: no tenim les paraules de pas en clar
- Formats suportats de hash a GSuite. SHA-1, MD5, crypt
- Es pot passar un hash al crear usuari. Cal posar-lo com a  base 16 bit hexadecimal-encoded hash value
- Laravel no utilitza SHA-1 -> tenir un camp extra amb el password de l'usuari també guardat en SHA-1

https://developers.google.com/admin-sdk/directory/v1/reference/users

hashFunction	string	Stores the hash format of the password property. We recommend sending the password property value as a base 16 bit hexadecimal-encoded hash value. Set the hashFunction values as either the SHA-1, MD5, or crypt hash format.

## Error: unauthorized_client error_description: "Client is unauthorized to retrieve access tokens using this method."

ÉS possible que a https://admin.google.com/AdminHome?chromeless=1#OGX:ManageOauthClients o domini admin.google.com i anar a Security/Configuracion Avanzada/Autenticació/Administrar el accesso de cliente APi
el client api no tingui el scope o scopes necessaris. Com saber el "nombre del cliente"?

ELS JSON estan a storage/app/gsuite_service_accounts

100002334584174231252: "client_email": "provalectura@eminent-tape-163119.iam.gserviceaccount.com",
                         "client_id": "100002334584174231252",

117357427466292859088: "client_email": "scool-41@scool-204309.iam.gserviceaccount.com",
                         "client_id": "117357427466292859088",

