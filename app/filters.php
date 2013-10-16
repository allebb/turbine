<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before(function($request) {
            //
        });


App::after(function($request, $response) {
            //
        });

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter('auth', function() {
            if (Auth::guest())
                return Redirect::guest('login');
        });


Route::filter('auth.basic', function() {
            // I have changed this as by default an email
            return Auth::basic('username');
        });



/*
  |--------------------------------------------------------------------------
  | Guest Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('guest', function() {
            if (Auth::check())
                return Redirect::to('/');
        });

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

Route::filter('csrf', function() {
            if (Session::token() != Input::get('_token')) {
                throw new Illuminate\Session\TokenMismatchException;
            }
        });

/**
 * Custom API auth filter for Turbine, requests that the username and the API key is honored by the
 * application and also that the API is in 'enabled' mode before actually responding to any API requests.
 */
Route::filter('auth.api', function() {
            if (Setting::getSetting('api_enabled') != 'true')
                return Response::json(array('error' => true, 'message' => 'API is in disabled mode'), 401);

            // Now we need to make an authentication request using the API key from the settings table.
            if (!Request::getUser() || !Request::getPassword()) {
                return Response::json(array('error' => true, 'message' => 'A valid API user and key is required'), 401);
            }
            $user = User::where('username', '=', Request::getUser())->first();
            // If NOT user and the API key doesn't match in the Settings table....
            // Can also use Request::getUser(); to get the HTTP Basic provided username too if required!
            if ($user && (Setting::getSetting('api_key') == Request::getPassword())) {
                Auth::login($user);
            } else {
                return Response::json(array('error' => true, 'message' => 'Invalid credentials'), 401);
            }
        });