<?php

// This 'view composers' will be moved into their own include file eventually but for speed I'm just added them here for now!
View::composer('*', function($view) {
            if (Auth::user()) {
                $view->with('useraccount', $useraccount = \User::find(\Auth::user()));
            }
        });


// Application routes
Route::resource('/', 'OverviewController', array('only' => array('index')));
Route::resource('rules', 'RulesController');
Route::resource('settings', 'SettingsController', array('only' => array('index')));
Route::get('logout', array('as' => 'logout', function() {
        Session::flush();
        Auth::logout();
        return Redirect::route('.index');
    }));