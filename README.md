# Technical Test - PHP/MySQL

Assumptions about server
---------------
* PHP, MySQL, Apache, Composer, GIT are installed and work properly.
* PHP has all necessary modules (like `mysqli`, `mbstring`).
* PHP sessions are available and session storage (by default `/var/lib/php/sessions`) is readable and writable for your web server user. Otherwise it will be impossible to pass CSRF check.
* MySQL transactions are available.
* Web server security cases are covered by server itself.

App was tested on
---------------
* PHP 7.2.24-0ubuntu0.18.04.3
* mysql  Ver 14.14 Distrib 5.7.29, for Linux (x86_64)
* Apache/2.4.29 (Ubuntu)
* Composer 1.6.3
* git version 2.17.1

Assumptions about PRD
---------------
* Normal prices should be provided for all available currencies.
* Special prices should be either absent or provided for all available currencies.
* We don't have separate dev env here, so `phpunit` dependency was added to `require`, not to `require-dev` composer section. 

External libraries
---------------
* `twig/twig` for front-end templates.
* `go/db` for DB access (with parametrized placeholders support).
* `phpunit/phpunit` for unit tests.

Getting Started
---------------
* Clone repo from [https://github.com/steel-archer/products-n-prices](https://github.com/steel-archer/products-n-prices)
```
git clone git@github.com:steel-archer/products-n-prices.git
```
* Go to your project directory.
```
cd products-n-price
```
* Initialize composer:
```
composer install
```
* Create a mysql user, database and tables:
* Replace `##PASSWORD##` in the file `migrations/init.sql` with the password which you want to use.
* (If necessary, also replace `localhost` in this file with your mysql server address)
* Log into mysql CLI as a user with admin priveleges and perform all mysql queries from `migrations/init.sql`.
* After that you can undo all your changes in `migrations/init.sql`
* Create a local config file from the template:
```
cp configs/config_local.php.template configs/config_local.php
```
* In this file replace `##PASSWORD##` with your mysql password.
* (If necessary, also replace `localhost` in this file with your mysql server address)

How to use this app
---------------
* All examples are provided for app root address `http://localhost/products-n-prices/`
* [Find a product](http://localhost/products-n-prices/?action=find)
* [Save a product](http://localhost/products-n-prices/?action=save)

Tests run
---------------
```
./vendor/bin/phpunit tests
```
