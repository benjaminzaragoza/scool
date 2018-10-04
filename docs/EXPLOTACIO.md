# Dades

- Domini: scool.cat
- Gestió DNS: Digital Ocean: https://cloud.digitalocean.com/networking/domains/scool.cat?i=c56b69
- Tenants: *.scool.cat Copy	directs to 128.199.44.54
- Digital Ocean (Compte administrador sergitur@iesebre.com)
- https://cloud.digitalocean.com
- El servei el tenim contractat a través de Globals (Josep Llaó)
- Dades servidor (scool-cat):
- Ubuntu 18.04 | 1 vCPUs | 1GB / 25GB Disk | ($5/mo) | 128.199.44.54 | 10.133.34.196

# Plantilla màquina (Laravel Forge)

https://forge.laravel.com/servers

- Usuari: sergitur@iesebre.com
- Server scool.cat: https://forge.laravel.com/servers/231518#/websites

# Acacha forge publish

Configuració:

ACACHA_FORGE_URL=https://forge.acacha.org
ACACHA_FORGE_EMAIL=sergiturbadenas@gmail.com
ACACHA_FORGE_SERVER=231518
ACACHA_FORGE_IP_ADDRESS=128.199.44.54
ACACHA_FORGE_SERVER_NAME=scool-cat
ACACHA_FORGE_DOMAIN=scool.cat
ACACHA_FORGE_PROJECT_TYPE=php
ACACHA_FORGE_SITE_DIRECTORY=/public
ACACHA_FORGE_SITE=617100
ACACHA_FORGE_GITHUB_REPO=acacha/scool

## Forge i Letsencrypt wildcard configuration

A https://forge.laravel.com/servers/231518/sites/614598#/certificates

crear un nou certificat tipus LetsEncrypt a domini cal posar:

 Domains: scool.cat,*.scool.cat
 Provider: Digital Ocean

- https://medium.com/@taylorotwell/wildcard-letsencrypt-certificates-on-forge-d3bdec43692a

# Eines extres instal·lades

 $ sudo apt-get install molly-guard joe

 # Configuracions Extres

  $ sudo apt-get php7.2-ldap

# Connexió al servidor via SSH

 ssh scool-cat_231518

Host scool-cat_231518
  Hostname 128.199.44.54
  User forge
  IdentityFile /home/sergi/.ssh/id_rsa
  Port 22
  StrictHostKeyChecking no

També podem utilitzar desdel projecte:

 $ php artisan publish:connect

# Fitxers privats que s'han d'instal·lar manualment al servidor explotació

S'ha de copiar de local a explotació (no estan a Github) les següents carpetes/fitxers:

- app/private_helpers
- storage/photos
- storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json


Directoris que s'han de crear:

 mkdir -p /home/forge/scool.cat/storage/app/iesebre/teacher_photos
 
# Configuració email

Al fitxer ewnv explotació utilitzem Mailgun:

MAIL_DRIVER=mailgun
MAILGUN_DOMAIN=mailgun.iesebre.com
MAILGUN_SECRET=SECRETE_HERE
# TODO Dinamically taken from TENANT INFO on database
MAIL_FROM_ADDRESS=noreply@iesebre.com
MAIL_FROM_NAME="Institut de l'Ebre"

# Configuració Google Apps

# Errors típics i solucions

## File does not exist quan necessitem Google Apps

Fitxer Json storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json


## 500 Key path "file:///home/forge/scool.cat/storage/oauth-public.key" does not exist or is not readable


```
php artisan passport:install
```

# Tunel SSH (base de dades ebre-escool)

sudo apt-get install autossh
(ssh-keygen? sinó tenim clau creada)
ssh-copy-id -i ~/.ssh/id_rsa -p 8022 sergi@escool.iesebre.com

EXPLOTACIó
autossh -M 10984 -o "PubkeyAuthentication=yes" -o "PasswordAuthentication=no" -i /home/forge/.ssh/id_rsa -R 3306:localhost:6606 sergi@escool.iesebre.com -p 8022

LOCAL:


SUPERVISOR

/etc/supervisor/conf.d/ebre_escool_autossh_tunel.conf
[program:ebre-escool-autossh-tunel]
process_name=%(program_name)s_%(process_num)02d
command=autossh -M 10984 -o "PubkeyAuthentication=yes" -o "PasswordAuthentication=no" -i /home/sergi/.ssh/id_rsa -L 3307:localhost:3306 sergi@185.13.76.85 -p 8022
autostart=true
autorestart=true
user=sergi
numprocs=1
redirect_stderr=true
stdout_logfile=/home/sergi/Code/acacha/scool/storage/logs/tunel.log
