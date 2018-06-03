# SimpleForumSoftware

Projekat iz Web programiranja. Bez nekih originalnih ideja. Prvi Laravel projekat.

| ![publicarea](doc/publicarea.png) | ![adminarea](doc/adminarea.png) |
|:---:|:---:|
| Deo za javnost | Deo za administratore |

## Proglašavam završenim

Implementirao sam najosnovnije funkcionalnosti koje neki forum software treba da ima, a pritom sam ispunio sve uslove projekta.

### Šta je odrađeno

TODO

### Šta je moglo da se odradi

* Glasanje ★
* Ćaskanje
* Moderatori ★
* Višejezičnost ★
* Privatne poruke
* Izbor šeme boja
* Ko je gledao profil
* Prijavljivanje neprikladnih poruka ★
* Praćenje tema, poruka i kategorija ★
* Prikazuj trenutnu aktivnost korisnika
* Registracija putem društvenih mreža
* Refaktorizacija
  * Trenutno je sve zbrda-zdola sklopljeno kako bi projekat što pre bio završen.
* Praćenje IP adresa korisnika
  * Da se osiguramo da korisnik nema više od jednog naloga.
* Izmena poruka ★
  * Verzije poruka se čuvaju u posebnoj tabeli. Admini imaju opciju da urade undo (posle ne može redo).
* Moj, centralizovan, hosting
  * Kao što nudi [Forumotion](https://www.forumotion.com/). Trenutno aplikaciju svako mora da skine i instalira na svom serveru. Postarao sam se da to bude što bezbolnije moguće.
* Lajkovanje/dislajkovanje poruka ★
  * Poruke sa negativnim rejtingom se sakrivaju (korisnik može da prikaže poruku ako želi). Korisnici koji daju loše rejtinge jer nemaju pametnija posla gube mogućnost da ostavljaju rejting. Admin može da poništi rejting ako proceni da nije validan.

★ Sve je pripremljeno (npr. u bazi), ali ipak nije implementirano.

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

## Istorijat

1. Radio ne prateći MVC.
2. Pokušao da napravim svoj MVC framework.
3. Skinuo Laravel i sve odradio ispočeteka.

## TODO

- Generisanje izvestaja (HTML, PDF, DOCX, XSLX, ...)
