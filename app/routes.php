<?php

// This 'view composers' will be moved into their own include file eventually but for speed I'm just added them here for now!
View::composer('*', function($view) {
            if (Auth::user()) {
                $view->with('useraccount', $useraccount = \User::with('person')->find(\Auth::user()->id));
            }
        });



Route::resource('/', 'OverviewController', array('only' => array('index')));
Route::resource('rules', 'RulesController');