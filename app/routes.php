<?php

// Application routes
Route::resource('/', 'OverviewController', array('only' => array('index')));
Route::resource('rules', 'RulesController');
Route::resource('settings', 'SettingsController', array('only' => array('index', 'store')));
Route::get('logout', array('as' => 'logout', function() {
        Session::flush();
        Auth::logout();
        return Redirect::route('.index');
    }));
Route::controller('action', 'UtilController');