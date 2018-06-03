# SimpleForumSoftware

Projekat iz Web programiranja. Bez nekih originalnih ideja. Prvi Laravel projekat.

| ![publicarea](doc/publicarea.png) | ![adminarea](doc/adminarea.png) |
|:---:|:---:|
| Deo za javnost | Deo za administratore |

## Proglašavam završenim

Implementirao sam najosnovnije funkcionalnosti koje neki forum software treba da ima, a pritom sam ispunio sve uslove projekta.

Nešto što nije odrađeno, a bilo je planirano na početku:

* *Glasanje*
* Ćaskanje
* *Moderatori*
* *Višejezičnost*
* Privatne poruke
* Ko je gledao profil
* Više tema (šema boja)
* *Prijavljivanje neprikladnih poruka*
* *Praćenje tema, poruka i kategorija*
* Prikazuj trenutnu aktivnost korisnika
* Registracija putem društvenih mreža
* Praćenje IP adresa korisnika
  * Da se osiguramo da korisnik nema više od jednog naloga.
* Moj, centralizovan, hosting
  * Kao što nudi [Forumotion](https://www.forumotion.com/). Trenutno aplikaciju svako mora da skine i instalira na svom serveru. Postarao sam se da to bude što bezbolnije moguće.
* *Lajkovanje/dislajkovanje poruka*
  * Poruke sa negativnim rejtingom se sakrivaju (korisnik može da prikaže poruku ako želi). Korisnici koji daju loše rejtinge jer nemaju pametnija posla gube mogućnost da ostavljaju rejting. Admin može da poništi rejting ako proceni da nije validan.

*Sve je pripremljeno (npr. u bazi se nalaze potrebne tabele i polja), ali ipak nije implementirano.*

## Instalacija

TODO

## Vendor

* [Faker](https://github.com/fzaninotto/Faker)
* [Toastr](https://github.com/CodeSeven/toastr)
* [FitText.js](https://github.com/davatron5000/FitText.js)
* [SCEditor](https://github.com/samclarke/SCEditor)
* [BBCode Parser](https://github.com/chriskonnertz/bbcode)
* [Active for Laravel](https://github.com/letrunghieu/active)
* [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
* [JQueryUI Nestable](https://github.com/dbushell/Nestable)
* [Laravel Online Users](https://github.com/thomastkim/laravel-online-users)

## Baza podataka
![model](doc/model.png)

## TODO

- Generisanje izvestaja (HTML, PDF, DOCX, XSLX, ...)
- Logovanje korisnickih akcija i gresaka
- *Izmena poruka*
  * Verzije poruka se čuvaju u posebnoj tabeli. Admini imaju opciju da urade undo (posle ne može redo).
