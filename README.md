Getting Started
---------------
* If you do not have [Composer](https://getcomposer.org/doc/00-intro.md), install it.
* Clone repo from [https://github.com/steel-archer/products-n-prices](https://github.com/steel-archer/products-n-prices)
```
git clone https://github.com/steel-archer/products-n-prices products-n-prices
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
* Log into mysql CLI as a user with admin priveleges and perform all mysql queries from this file.
* After that you can undo all your changes in `migrations/init.sql`
* Make a local config file from the template:
```
cp configs/config_local.php.template configs/config_local.php
```
* Replace ##PASSWORD## with your mysql password.
* (If necessary, also replace `localhost` in this file with your mysql server address)
