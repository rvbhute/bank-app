<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->json(['message' => 'Hi, this is the Bank App!']);
});

Route::post('users', 'UserController@openBankAccount');
Route::post('users/overdraft', 'UserController@switchOverdraft');
//Route::delete('user', 'UserController@closeBankAccount');

Route::post('transactions/credit', 'TransactionController@creditAccount');
Route::post('transactions/debit', 'TransactionController@debitAccount');
//Route::get('transactions', 'Transactioncontroller@getBalanceStatement');
