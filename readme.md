## Bank App

### Installation Instructions

````
$ touch database/db.sqlite
$ composer install
$ cp .env.example .env
````

Set the SQLite path in `DB_DATABASE` in `.env` file.

````
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
* There is config file `bank_app.config` in the config directory. It contains two config variables - `overdraft` - overdraft amount, and `minimum` - the minimum balance.


Some exceptions are handled by code numbers.

* 7000 - Withdrawal crossing minimum balance
* 7001 - Attempting to turn off overdraft facility when balance is below minimum level
* 7002 - Withdrawal crossing overdraft limit
* 7004 - Trying to close account when balance is below minimum level
