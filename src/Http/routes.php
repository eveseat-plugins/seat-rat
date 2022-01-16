<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\RattingMonitor\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'ratting',
], function () {

    Route::get('/character', [
        'as'   => 'rattingmonitor.character',
        'uses' => 'RattingMonitorController@character',
        'middleware' => 'can:inventory.view_inventory'
    ]);

    Route::get('/user', [
        'as'   => 'rattingmonitor.user',
        'uses' => 'RattingMonitorController@user',
        'middleware' => 'can:inventory.view_inventory'
    ]);
});