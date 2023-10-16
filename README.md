SUPERAUTOS
=======

## TALDEKIDEEN IZENAK

Iker Tranchand, Iñigo Dueñas, Ander Algar, Adrián Fernández, Oier Urquijo eta Juan Belio.

## Docker bidez proiektua hedatzeko instrukzio zehatzak:

(Instrukzio hauek dira docker, docker-composite eta git instalatuta eta konfiguratuta daudela suposatuz. Bahimen errore bat badago sudo erabili).
(PHPMYADMIN-en sartzeko erabiltzailea "root" da eta pasahitza "root" da).

Lehenengo entrega_1 irudia sortu beharko dugu superautosweb direktorioan Dockerfile fitxategia erabiliz. Horretarako superautosweb direktorio barruan gaudenean hurrengo komandoa idatzi beharko dugu: 
```bash
$ docker build -t "entrega_1"
```

Ondoren hurrengo komandoa idatziko dugu docker kontainerrak hasieratzeko:

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

Komandoa jarri ondoren, pasahitza eskatuko digu. Beraz, pasahitza jarriko dugu. Pasahitza "root" da.

Ondoren esango diogu "database" izeneko datu basea erabili nahi dugula:
```bash
$ USE database;
```

Gero "database.sql" fitxategia inportatuko dugu hau jarriz:
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

# Erabili dugun kodea baina gurea ez dena. 
Hemen adieraziko dugu nork egin duen eta nondik atera dugun gu ez egindako kodea:

SQL konexioa egiteko web orriarekin:

Egilea: MagtimusPro

URL: https://www.youtube.com/watch?v=veoZts7H-ZI&list=PLAFTVct4TDOa1HHObYNcGqRXsYTcCnfF4&index=2

Data: 2020ko abuztuaren 7an

Oharra: aldaketak egin ditugu gure web orri sistemara adaptatzeko. Aldaketak izan dira bariableetan.


Erabiltzailearen izena gordetzeko sesioan:

Egilea: Rubin Porwal

URL: https://stackoverflow.com/questions/41391862/how-to-access-php-session-variable-in-javascript

Data: 2016ko abenduaren 30an

Oharra: aldaketak egin ditugu gure web orri sistemara adaptatzeko. Aldaketak izan dira bariableetan.


NAN formatua konprobatzeko kodea:

Egilea: kit

URL: https://es.stackoverflow.com/questions/271449/pattern-dni-como-puedo-engancharlo-para-que-funcione

Data: 2019ko ekainaren 12an

Oharra: aldaketak egin ditugu gure web orri sistemara adaptatzeko. Aldaketak izan dira bariableetan.

