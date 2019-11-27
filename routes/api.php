<?php

use \GuzzleHttp\Client;

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

Route::post('login', 'UsersController@login');
Route::post('register', 'UsersController@register');
Route::post('archive', 'SettingsController@archive');
Route::get('logout', 'UsersController@logout');
Route::get('download', 'ReportsController@download');
Route::get('archive/settings', 'SettingsController@getSettings');

Route::get('inn/{voen}', function ($voen) {
    try {
        $client = new \GuzzleHttp\Client();
        $options = [
            'form_params' => [
                "tip" => "P",
                "voenOrName" => "V",
                "voen" => $voen,
                "submit" => "Yoxla",
            ],
        ];

        $res = $client->request('POST', 'https://www.e-taxes.gov.az/ebyn/payerOrVoenChecker.jsp', $options);

        return Response::json(array("type" => "success", "result" => $res->getBody()->getContents()));

    } catch (\Exception $e) {

        return Response::json(array("type" => "error", "result" => $e->getMessage()));

    }
});

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::get('last/transaction', 'TransactionController@getLastIdTransaction');
    Route::get('oputax', 'InvoiceController@oputax');
    Route::get('balancetax', 'InvoiceController@balancetax');
    Route::get('transaction/delete/{id}', 'TransactionController@delete');

    Route::post('invoice/destroy', 'InvoiceController@destroy');
    Route::post('profile', 'ProfileController@update');

    Route::resource('transaction/types', 'TransactionTypeController');
    Route::resource('invoice', 'InvoiceController');
    Route::resource('transaction', 'TransactionController');
    Route::resource('fichetypes', 'FicheTypeController');

    Route::post('reports/export', 'ReportsController@export');
    Route::post('reports/remains', 'ReportsController@remains');
    Route::post('reports/saldo', 'ReportsController@saldo');
    Route::post('reports/balance', 'ReportsController@balance');
    Route::post('reports/oputax', 'ReportsController@oputax');
    Route::post('reports/balancetax', 'ReportsController@balancetax');

});
