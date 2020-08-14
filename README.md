# ORM test
Testy bibliotek ORM:
- CycleORM 1.2
- DoctrineORM 2.7

## Słowo od autora
*Całość jest w fazie BETA więc proszę o wyrozumiałość. Czasy są wrzucone poglądowo, bo zazwyczaj uruchamiałem każdy z 
testów wielokrotnie i wybierałem najbardziej zbliżone wyniki. Używam do celów osobistych, ale jak masz chęć pomóc, albo 
zwrócić uwagę, że coś źle robię to się nie obrażę :)*

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

W planach:
- Usuwanie użytkowników (pojedyńcze i masowe)
- Aktualizacja użytkowników (pojedyńcza i masowa)
- Stworzenie obiektu `Product` z embedami oraz testy
    - Dodanie (na jednej i wielu transakcjach)
    - Odczyt (pojedyńczy i masowy)
    - Usuwanie (pojedyńcze i masowe)
    - Aktualizacja (pojedyńcza i masowa)
- Stworzenie obiektu `Lead` z relacjami do `Product` i `User`
    - Dodanie (na jednej i wielu transakcjach)
    - Odczyt (pojedyńczy i masowy)
    - Usuwanie (pojedyńcze i masowe)
    - Aktualizacja (pojedyńcza i masowa)
- Testy DBAL
    - Dodanie (na jednej i wielu transakcjach)
    - Odczyt (pojedyńczy i masowy)
    - Usuwanie (pojedyńcze i masowe)
    - Aktualizacja (pojedyńcza i masowa)
- Spisanie wniosków końcowych dotyczących zalet i wad każdego z ORM

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

Cycle ORM tutaj deklasuje Doctrine o ~50% jeśli chodzi o czas. Jeżeli chodzi o ilość użytej pamięci to także widać 
sporą różnicę. Dla pewności powtórzyłem test kilkukrotnie, ale dystans był zawsze niemal identyczny.

PS. Wiartaki w moim laptopie się strasznie męczyły przy Doctrine, co też świadczy o optymalizacji. W przypadku Cycle 
aż takiej tragedii nie było.

#### Pobieranie użytkowników

##### Wyciąganie pojedyńczego obiektu

Wyciąganie pojedyńczego obiektu użytkownika powtórzone 1000 razy.

*Doctrine ORM*

Istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/doctrine-orm orm:user:get -c 1000 -f
Cleanup database
Create users in database
Single fetch mode
------ OVERALL ------
Memory: 14336 kB
Time: 375 ms
```

Nie istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/doctrine-orm orm:user:get -c 1000
Cleanup database
Single fetch mode
------ OVERALL ------
Memory: 6144 kB
Time: 122 ms
```

*Cycle ORM*

Istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/cycle-orm orm:user:get -c 1000 -f
Cleanup database
Create users in database
Single fetch mode
------ OVERALL ------
Memory: 12288 kB
Time: 334 ms
```

Nie istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/cycle-orm orm:user:get -c 1000
Cleanup database
Single fetch mode
------ OVERALL ------
Memory: 6144 kB
Time: 81 ms
```

*Wnioski*

Cycle ORM wymagał mniej pamięci jak zwykle. Czasy pobierania są zbliżone z niewielkim wskazaniem na Cycle ORM. 
Ciekawe jednak jest to, że w przypadku pobierania nie istniejących wpisów Cycle ORM okazał się o ~33% szybszy. 
Oba notowały większ lub mniejsze skoki w czasie wykonania, ale Doctrine miał tutaj dużo większą rozbieżność.

##### Wyciąganie listy obiektów

Wyciąganie listy 1000 obiektów użytkownika powtórzone 1000 razy.

*Doctrine ORM*

Istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/doctrine-orm orm:user:get -c 1000 -f -l
Cleanup database
Create users in database
List fetch mode
------ OVERALL ------
Memory: 16384 kB
Time: 10357 ms
```

Nie istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/doctrine-orm orm:user:get -c 1000 -l
Cleanup database
List fetch mode
------ OVERALL ------
Memory: 8192 kB
Time: 160 ms
```

*Cycle ORM*

Istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/cycle-orm orm:user:get -c 1000 -f -l
Cleanup database
Create users in database
List fetch mode
------ OVERALL ------
Memory: 12288 kB
Time: 12233 ms
```

Nie istniejące wpisy w bazie danych

```
root@e64bed994f5d:/app# bin/cycle-orm orm:user:get -c 1000 -l
Cleanup database
List fetch mode
------ OVERALL ------
Memory: 6144 kB
Time: 83 ms
```

*Wnioski*

Doctrine ORM wygrał o prawie 2 sekundy (20%) jeśli chodzi o odczyt wielu rekordów na raz, jednak zużył o ~25% więcej 
pamięci. Natomiast Cycle ORM okazał się lepszy jeśli chodzi o czas odczytu nie istniejących danych o ~50%.

## Uwagi

*Cycle ORM*

- Nie ma opcji limitowania wyników na `findAll` w obiekcie repozytorium. Trzeba to robić przez `QueryBuilder`.
- Metoda `findAll` w obiekcie repozytorium zwraca tablicę. Nie ma obiektu kolekcji.

*Doctrine ORM*

- Nie ma żadnych opcji na `findAll` w obiekcie repozytorium. Trzeba to robić przez `QueryBuilder`.
- Metoda `findAll` w obiekcie repozytorium zwraca tablicę. Nie ma obiektu kolekcji.
