<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use \Setting;

class SettingsController extends \BaseController
{

    function __construct()
    {
        // Require that the user is logged in!
        $this->beforeFilter('auth.basic');
        // We want to enable CSFR protection on both the 'store' action.
        $this->beforeFilter('csrf', array('on' => 'store'));
    }

    /**
     * Display all the current 'user' system settings and a form to enable them to make changes to them.
     */
    public function index()
    {
        $settings = Setting::where('usersetting', true)->orderBy('name')->get();
        return View::make('settings.index')
                        ->with('title', 'Settings')
                        ->with('settings', $settings);
    }

    /**
     * Store a newly created resource in storage.
     * ...but in our case are we are updating a whole load of settings this is really the 'update' action.
     */
    public function store()
    {
        $fields = Input::except('_token');
        foreach ($fields as $setting => $value) {
            $data = Setting::where('name', $setting)->first();
            $data->svalue = $value;
            $data->save();
        }
        return Redirect::back()
                        ->with('flash_info', 'Application settings have been updated successfully!');
    }

}