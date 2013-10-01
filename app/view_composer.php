<?php

View::composer('*', function($view) {
            if (Auth::user()) {
                $view->with('useraccount', $useraccount = \User::find(\Auth::user()));
            }

            // Make the current system version avaliable to all pages.
            $view->with('turbineversion', Setting::getSetting('version'));
        });