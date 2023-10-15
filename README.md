SUPERAUTOS
=======

## TALDEKIDEEN IZENAK

Iker Tranchand, Iñigo Dueñas, Ander Algar, Adrián Fernández, Oier Urquijo eta Juan Belio.

## Docker bidez proiektua hedatzeko instrukzio zehatzak:

(Instrukzio hauek dira docker eta git instalatuta eta konfiguratuta daudela suposatuz) 

Lehenengo superautosweb direktorioan hurrengo komandoa idatzi behar dugu: 
```bash
$ docker-compose up -d
```

Docker kontainerrak hasieratu ondoren, database.sql fitxategia kopiatuko dugu superautosweb-db-1 kontainerrean. Kontainerrak ikusteko hau jarri beharko dugu:
```bash
$ docker cp database.sql superautosweb-db-1:/
```

Ondoren, superautosweb-db-1 exekutatuko dugu kontsolan sartzeko hau jarriz:
```bash
$ docker exec -it superautosweb-db-1 /bin/bash
```

Gero mysql sartuko gara honako hau idatziz kontsolan:
```bash
$ mysql -u root -p
```

Ondoren esango diogu database izeneko datu basea erabili nahi dugula:
```bash
$ USE database;
```

Gero database.sql fitxategia inportatuko dugu hau jarriz:
```bash
$ source database.sql;
```

Prosezua amaitzerakoan CTRL+C emango diogu ateratzeko eta ondoren exit jarriko dugu kontsolan:
```bash
$ exit
```

Orain nabigatzailean sartuko gara eta hurrengo URL jarriko dugu: localhost:81/hasiera.php

Amaitzeko kontainerrak amatatu nahi baditugu honako hau jarriko dugu kontsolan:
```bash
$ docker-compose stop
```

# Docker LAMP
Linux + Apache + MariaDB (MySQL) + PHP 7.2 on Docker Compose. Mod_rewrite enabled by default.

