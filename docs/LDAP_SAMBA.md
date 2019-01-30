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

El rid va a data 29-01-2019 per Joaquin Panisello Gamundi 9834
 
# COMPTES DE MÀQUINES DE DOMINI

Exemple nom cn=a33pc01$ 

# CAL MIRAR

Codificació caràcters extranys quan el nom té accents o similar

# Exemple usuari

version: 1

dn:: Y249Sm9hcXXDrW4gUGFuaXNlbGxvIEdhbXVuZGksb3U9UHJvZmVzLG91PUFsbCxkYz1pZXN
 lYnJlLGRjPWNvbQ==
objectClass: extensibleObject
objectClass: inetOrgPerson
objectClass: irisPerson
objectClass: sambaSamAccount
objectClass: shadowAccount
objectClass: posixAccount
objectClass: person
objectClass: top
cn:: Sm9hcXXDrW4gUGFuaXNlbGxvIEdhbXVuZGk=
gidNumber: 513
homeDirectory: /samba/homes/joaquinpanisello
sambaSID: S-1-5-21-4045161930-1404234508-1517741366-9834
sn: Panisello Gamundi
uid: joaquinpanisello
uidNumber: 4917
dateOfBirth: 1972-12-10
email: donquimo@hotmail.com
gender: M
givenName:: Sm9hcXXDrW4=
homePhone: 977501827
homePostalAddress:: UmFtYmxhIFBvcGV1IEZhYnJhIDUwLCAzwrogMcKq
irisPersonalUniqueID: 52601055D
irisPersonalUniqueIDType: 1
jpegPhoto:: /9j/4AAQSkZJRgABAQEASABIAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1
l: Tortosa
loginShell: /bin/bash
mobile: 667205595
postalCode: 43500
sambaAcctFlags: [UX          ]
sambaBadPasswordCount: 0
sambaBadPasswordTime: 0
sambaDomainName: INSEBRE
sambaHomeDrive: U:
sambaHomePath: \\samba01\joaquinpanisello
sambaLMPassword: C102848EBB79A0C2570A3F52BFFF0944
sambaLogonScript: professorat.bat
sambaLogonTime: 0
sambaMungedDial: IAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAA
 gACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAIAAgACAAUAAQAB
 oACAABAEMAdAB4AEMAZgBnAFAAcgBlAHMAZQBuAHQANTUxZTBiYjAYAAgAAQBDAHQAeABDAGYAZ
 wBGAGwAYQBnAHMAMQAwMGUwMDAxMBYAAAABAEMAdAB4AEMAYQBsAGwAYgBhAGMAawASAAgAAQBD
 AHQAeABTAGgAYQBkAG8AdwAwMTAwMDAwMCIAAAABAEMAdAB4AEsAZQB5AGIAbwBhAHIAZABMAGE
 AeQBvAHUAdAAqAAIAAQBDAHQAeABNAGkAbgBFAG4AYwByAHkAcAB0AGkAbwBuAEwAZQB2AGUAbA
 AwMCAAAgABAEMAdAB4AFcAbwByAGsARABpAHIAZQBjAHQAbwByAHkAMDAgAAIAAQBDAHQAeABOA
 FcATABvAGcAbwBuAFMAZQByAHYAZQByADAwGAACAAEAQwB0AHgAVwBGAEgAbwBtAGUARABpAHIA
 MDAiAAIAAQBDAHQAeABXAEYASABvAG0AZQBEAGkAcgBEAHIAaQB2AGUAMDAgAAIAAQBDAHQAeAB
 XAEYAUAByAG8AZgBpAGwAZQBQAGEAdABoADAwIgACAAEAQwB0AHgASQBuAGkAdABpAGEAbABQAH
 IAbwBnAHIAYQBtADAwIgACAAEAQwB0AHgAQwBhAGwAbABiAGEAYwBrAE4AdQBtAGIAZQByADAwK
 AAIAAEAQwB0AHgATQBhAHgAQwBvAG4AbgBlAGMAdABpAG8AbgBUAGkAbQBlADAwMDAwMDAwLgAI
 AAEAQwB0AHgATQBhAHgARABpAHMAYwBvAG4AbgBlAGMAdABpAG8AbgBUAGkAbQBlADAwMDAwMDA
 wHAAIAAEAQwB0AHgATQBhAHgASQBkAGwAZQBUAGkAbQBlADAwMDAwMDAw
sambaNTPassword: A315151303B5D9F937386EBCF5AF9F89
sambaPrimaryGroupSID: S-1-5-21-4045161930-1404234508-1517741366-513
sambaPwdLastSet: 2147483647
shadowLastChange: 17794
sn1: Panisello
sn2: Gamundi
userPassword:: e01ENX1jcmYya2Rsd1gwSURBaktiUTR3dGRRPT0=

 
