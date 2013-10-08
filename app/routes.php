<?php

/**
 * Standard application routes
 */
Route::resource('/', 'OverviewController', array('only' => array('index')));
Route::resource('rules', 'RulesController');
Route::resource('settings', 'SettingsController', array('only' => array('index', 'store')));
Route::controller('action', 'UtilController');
Route::get('logout', array('as' => 'logout', function() {
        Session::flush();
        Auth::logout();
        return Redirect::route('.index');
    }));
    
/**
 * API route grouping
 */
Route::group(array('prefix' => 'api/'), function() {

            Route::resource('rule', 'api\RulesController', array(
                'only' => array(
                    'index',
                    'show',
                    'store',
                    'update',
                    'destroy',
            )));

            Route::resource('target', 'api\TargetController', array(
                'only' => array(
                    'store', // Creates a new target and 'associates its against an existing rule.
                    'destroy', // Delete an existing target.
            )));
        });