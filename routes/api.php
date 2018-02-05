<?php

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

Route::post('account', 'AccountController@openBankAccount');

Route::group(['middleware' => ['active']], function () {
    Route::get('account', 'AccountController@viewBankAccount');
    Route::post('account/delete', 'AccountController@closeBankAccount');
    Route::post('account/overdraft', 'AccountController@switchOverdraftFlag');


    Route::post('transactions/credit', 'TransactionController@creditAccount');
    Route::post('transactions/debit', 'TransactionController@debitAccount');
    //Route::get('transactions', 'Transactioncontroller@getBalanceStatement');
});
