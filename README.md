# ORM test
Testy bibliotek ORM:
- CycleORM 1.0
- DoctrineORM 2.7

*! Całość jest w fazie BETA więc proszę o wyrozumiałość. Czasy są wrzucone poglądowo, bo zazwyczaj uruchamiałem każdy z 
testów wielokrotnie i wybierałem najbardziej zbliżone wyniki*

## Uruchomienie

Należy mieć zainstalowany `Docker` oraz `Docker Compose`.

Aby uruchomić należy wejść do katalogu `.docker` i wywołać polecenie `docker-compose up`.

## Komendy

Cycle ORM
```
bin/cycle-orm
```

Doctrine ORM
```
bin/doctrine-orm
```

## Obiekty

### User

Zbudowany wyłącznie z typów prostych, aby nie zaburzać wyników pomiarów dla funkcjonowania samego mechanizmu zapisu 
danych w ORM.

## Testy

### Tworzenie użytkowników

#### Dodanie 10k użytkowników

Test zakłada stworzenie 10k użytkowników.

##### Na jednej transakcji

Zapis odbywa się po utworzeniu 10k obiektów użytkownika.

*Doctrine ORM*

```
root@e64bed994f5d:/app# bin/doctrine-orm orm:user:create -c 10000
Cleanup database
Single transaction mode
------ OVERALL ------
Memory: 65536 kB
Time: 1765 ms
------ SAVE ------
Memory: 65536 kB
Time: 1211 ms
```

*Cycle ORM*

```
root@e64bed994f5d:/app# bin/cycle-orm orm:user:create -c 10000
Cleanup database
Single transaction mode
------ OVERALL ------
Memory: 51200 kB
Time: 1678 ms
------ SAVE ------
Memory: 51200 kB
Time: 1197 ms
```

*Wnioski*

Cycle ORM poradził sobie lepiej, ale szału nie ma jeśli chodzi o czas wykonania. Natomiast zdecydowanie widać różnicę 
przy ilości użytej pamięci.

##### Na wielu transakcjach

Każdy rekord jest zapisywany do bazy danych pojedyńczo.

*Doctrine ORM*

```
root@e64bed994f5d:/app# bin/doctrine-orm orm:user:create -c 10000 -m
Cleanup database
Multiple transactions mode
------ OVERALL ------
Memory: 32768 kB
Time: 322439 ms
------ SAVE ------
Memory: 32768 kB
Time: 321350 ms
```

*Cycle ORM*

```
root@e64bed994f5d:/app# bin/cycle-orm orm:user:create -c 10000 -m   
Cleanup database
Multiple transactions mode
------ OVERALL ------
Memory: 26624 kB
Time: 166848 ms
------ SAVE ------
Memory: 26624 kB
Time: 165814 ms
```

*Wnioski*

Cycle ORM tutaj deklasuje Doctrine o 50% jeśli chodzi o czas. Jeżeli chodzi o ilość użytej pamięci to także widać 
sporą różnicę. Dla pewności powtórzyłem test kilkukrotnie, ale dystans był zawsze niemal identyczny.

PS. Wiartaki w moim laptopie się strasznie męczyły przy Doctrine, co też świadczy o optymalizacji. W przypadku Cycle 
aż takiej tragedii nie było.

#### Limit 128M

Test zakłada utworzenie jak najwiekszej ilości użytkowników przed przekroczeniem 128MB pamięci.


