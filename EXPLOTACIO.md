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