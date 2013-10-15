<?php

/**
 * Standard application routes
 */
Route::get('/', array('as' => '.index', function() {
        // Decided to remove the redundent 'OverviewController' as there was little point of using it!
        // Instead I am now using the Redirect::route to redirect to the rules controller.
        // Note that I've named this route '.index' as this was the generated controller name originally.. this stops
        // issues with the existing template rules which are trying to generate URL's from the route names..
        return Redirect::route('rules.index');
    }));
Route::resource('rules', 'RulesController');
Route::resource('settings', 'SettingsController', array('only' => array('index', 'store')));
Route::controller('password', 'PasswordController');
Route::controller('action', 'UtilController');

/**
 * API route grouping
 */
Route::group(array('prefix' => 'api/v1/'), function() {
            // I won't specify the 'only' methods as the extending API controller will respond with an JSON request for all missingMthods()
            Route::resource('rule', 'api\v1\RulesController');
            Route::resource('target', 'api\v1\TargetController');
        });