# SimpleForumSoftware
Projekat iz Web programiranja. Prva Laravel aplikacija. Bez nekih previše originalnih ideja.

| ![publicarea](doc/publicarea.png) | ![adminarea](doc/adminarea.png) |
|:---:|:---:|
| Javni deo | Administratorski panel |

## O projektu
Do sada sam implementirao neke najosnovnije funkcionalnosti forum softvera.

### Šta bi još moglo da se odradi

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
  * Sve je zbrda-zdola sklopljeno kako bi projekat što pre bio završen.
* Praćenje IP adresa korisnika
  * Da se osiguramo da korisnik nema više od jednog naloga.
* Moj, centralizovan, hosting
  -  Kao što nudi [Forumotion](https://www.forumotion.com/). Trenutno aplikaciju svako mora da skine i instalira na svom serveru.
* Izmena poruka ★
  * Sve verzije poruka se čuvaju u posebnoj tabeli. Te verzije su vidljive samo moderatorima i korisniku koji je poruku poslao. Postoji opcija da se poruka vrati u pređašnje stanje.
* Lajkovanje/dislajkovanje poruka ★
  * Poruke sa negativnim rejtingom se sakrivaju (korisnik može da prikaže poruku ako želi). Korisnici koji daju loše rejtinge jer nemaju pametnija posla gube mogućnost da ostavljaju rejting. Admin može da poništi rejting ako proceni da nije validan.

★ *Sve je pripremljeno, ali ipak nije implementirano.*

## Instalacija
### Localhost
1. Instalirati:
    * [Node JS](https://nodejs.org/en/)
    * [Composer](https://getcomposer.org/download/)
    * PHP i bazu podataka
        * [Laragon Mint](https://laragon.org/download/index.html)
1. Kreirati novu bazu podataka.
1. Registrovati [Invisible reCAPTCHA](https://www.google.com/recaptcha/admin).
    * Dobija se `CAPTCHA_SITE_KEY` i `CAPTCHA_SECRET_KEY`.
1. Klonirati ili preuzeti ovaj Git repozitorijum.
1. U komandoj liniji izvršiti slećede naredbe:
    * `npm install`
    * `composer install`
    * `php artisan key:generate`
        * Dobija se `APP_KEY`.
1. Preimenovati `.env.example` u `.env` i podesiti sve kako treba.
1. Izvršiti `php artisan migrate` kako bi se kreirale potrebne tabele u bazi.
1. Izvršiti `php artisan serve` kako bi sajt bio vidljiv računarima u lokalnoj mreži.

## Testiranje

Napravio sam gomilu sidova koji omogućavaju da se baza popuni nasumično generisanim podacima. Pokreću se naredbom `php artisan db:seed`.

Kao mejl server koristio sam [MailTrap](https://mailtrap.io/), lažan SMTP server namenjen upravo testiranju mejl poruka.

## Vendor

* [fzaninotto](https://github.com/fzaninotto)/**[Faker](https://github.com/fzaninotto/Faker)**
* [letrunghieu](https://github.com/letrunghieu)/**[active](https://github.com/letrunghieu/active)**
* [CodeSeven](https://github.com/CodeSeven)/**[toastr](https://github.com/CodeSeven/toastr)**
* [dbushell](https://github.com/dbushell)/**[Nestable](https://github.com/dbushell/Nestable)**
* [samclarke](https://github.com/samclarke)/**[SCEditor](https://github.com/samclarke/SCEditor)**
* [chriskonnertz](https://github.com/chriskonnertz)/**[bbcode](https://github.com/chriskonnertz/bbcode)**
* [davatron5000](https://github.com/davatron5000)/**[FitText.js](https://github.com/davatron5000/FitText.js)**
* [barryvdh](https://github.com/barryvdh)/**[laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)**
* [thomastkim](https://github.com/thomastkim)/**[laravel-online-users](https://github.com/thomastkim/laravel-online-users)**
* [Jimmy-JS](https://github.com/Jimmy-JS)/**[laravel-report-generator](https://github.com/Jimmy-JS/laravel-report-generator)**

## Baza podataka
![model](doc/model.png)

