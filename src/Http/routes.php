<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\RattingMonitor\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'ratting',
], function () {

    Route::get('/character', [
        'as'   => 'rattingmonitor.character',
        'uses' => 'RattingMonitorController@character',
        'middleware' => 'can:rattingmonitor.cat'
    ]);

    Route::get('/user', [
        'as'   => 'rattingmonitor.user',
        'uses' => 'RattingMonitorController@user',
        'middleware' => 'can:rattingmonitor.cat'
    ]);

    Route::get('/suggestions/systems', [
        'as'   => 'rattingmonitor.systems',
        'uses' => 'RattingMonitorController@systems',
        'middleware' => 'can:rattingmonitor.cat'
    ]);

    Route::get('/about', [
        'as'   => 'rattingmonitor.about',
        'uses' => 'RattingMonitorController@about',
        'middleware' => 'can:rattingmonitor.cat'
    ]);
});