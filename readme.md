## Bank App

### Installation Instructions

````
$ touch database/db.sqlite
````

Set the SQLite path in `DB_DATABASE` in `.env` file.

````
$ composer install
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan serve
````

### Endpoints

Please see included Postman file and environment for the routes.

* Open account
* Apply overdraft (flag for overdraft of ₹ 1,000)
* Deposit funds
* Withdraw funds
* Display balance
* Close account

#### Some Points

* Statement of transactions not implemented.
* Input is taken in rupees, e.g. `1500.75`but all values are stored and calculated in paise - `150075`, i.e. lowest common denomination.
* Display is also in paise for now. Transformation needs to be implemented.
