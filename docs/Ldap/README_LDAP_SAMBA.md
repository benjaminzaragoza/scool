# Adlap2 | Adldap2-laravel

## Configuració. Fitxer config/ldap.php

## Documentació

- https://adldap2.github.io/Adldap2-Laravel

# Questions a tenir en compte

- SIDS:
  - QUIN SID ASSIGNAR?
  - Paràmetre Ldap: Paràmetre Ldap:  
  - Part FIXe i part relativa -> RID
  - USUARIS PER DEFECTE LDAP ambs sids especifics
    - root?  RID 500 associat a root del sistema
    - guest/nobody 501 Associat a cap usuari
  - http://acacha.org/mediawiki/Samba#SIDS
  
CONFIGURACIÓ BÀSICA LDAP:

Objecte amb la info:

cn: sambaDomainName=INSEBRE,ou=All,dc=iesebre,dc=com

PREFIX: S-1-5-21-4045161930-1404234508-1517741366

INFO DE l'OBJECTE:
```
dn: sambaDomainName=INSEBRE,ou=All,dc=iesebre,dc=com
objectClass: sambaUnixIdPool
objectClass: sambaDomain
gidNumber: 1000
sambaDomainName: INSEBRE
sambaSID: S-1-5-21-4045161930-1404234508-1517741366
uidNumber: 1142
sambaAlgorithmicRidBase: 1000
sambaForceLogoff: -1
sambaLockoutDuration: 30
sambaLockoutObservationWindow: 30
sambaLockoutThreshold: 0
sambaLogonToChgPwd: 0
sambaMaxPwdAge: -1
sambaMinPwdAge: 0
sambaMinPwdLength: 5
sambaNextRid: 1137
sambaNextUserRid: 1000
sambaPwdHistoryLength: 0
sambaRefuseMachinePwdChange: 0
```

Veure que fa ebre-escool i millorar:
- 

RIDS (http://acacha.org/mediawiki/Samba#SIDS)
 - 512 Domain Admins
 - 513 Domain users
 
# COMPTES USUARI

El rid va a data 29-01-2019 per últim 9834
 
# COMPTES DE MÀQUINES DE DOMINI

Exemple nom cn=a33pc01$ 

# CAL MIRAR

Codificació caràcters extranys quan el nom té accents o similar

# Exemple usuari

version: 1

dn:: Y249Sm9hcXXDrW4gUGFuaXNlbGxvIEdhbXVuZGksb3U9UHJvZmVzLG91PUFsbCxkYz1pZXN
 lYnJlLGRjPWNvbQ==
 
# OBJECT CLASSES
objectClass: extensibleObject
objectClass: inetOrgPerson
objectClass: irisPerson
objectClass: sambaSamAccount
objectClass: shadowAccount
objectClass: posixAccount
objectClass: person
objectClass: top

cn:: Sm9hcXXDrW4gUGFuaXNlbGxvIEdhbXVuZGk=

# PERSON

## NAME

sn: SN1 SN2
sn1: SN1
sn2: SN2
givenName:: Sm9hcXXDrW4=

# Altres dades personals

dateOfBirth: 1972-12-10
gender: M

## IDENTIFIERS

irisPersonalUniqueID: 52601055D
irisPersonalUniqueIDType: 1

## LOCATION/ADDRESS

homePostalAddress:: UmFtYmxhIFBvcGV1IEZhYnJhIDUwLCAzwrogMcKq
l: Tortosa
postalCode: 43500

## EMAILS, PHONES AND MOBILES

email: donquimono@hotmail.com

homePhone: 977501827

mobile: 667205595

## FOTO

jpegPhoto:: /9j/4AAQSkZJRgABAQEASABIAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1

#POSIX ACCOUNT (LINUX ACCOUNT)

uid: sn1sn2
uidNumber: 4917
gidNumber: 513
homeDirectory: /samba/homes/sn1sn2
loginShell: /bin/bash
shadowLastChange: 17794
userPassword:: e01ENX1jcmYya2Rsd1gwSURBaktiUTR3dGRRPT0=


# SAMBA ACCOUNT (WINDOWS ACCOUNT)

sambaSID: S-1-5-21-4045161930-1404234508-1517741366-9834
sambaAcctFlags: [UX          ]
sambaBadPasswordCount: 0
sambaBadPasswordTime: 0
sambaDomainName: INSEBRE
sambaHomeDrive: U:
sambaHomePath: \\samba01\sn1sn2
sambaLMPassword: C102848EBB79A0C2570A3F52BFFF0944
sambaLogonScript: professorat.bat
sambaLogonTime: 0
sambaMungedDial: IAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgAC....
sambaNTPassword: A315151303B5D9F937386EBCF5AF9F89
sambaPrimaryGroupSID: S-1-5-21-4045161930-1404234508-1517741366-513
sambaPwdLastSet: 2147483647

# EBRE-ESCOOL

TODO
