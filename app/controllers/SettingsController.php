<?php

class SettingsController extends \BaseController
{

    function __construct()
    {
        $this->beforeFilter('auth.basic');
        $this->beforeFilter('csrf', array('on' => 'store'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = Setting::where('usersetting', true)->get();
        return View::make('settings')
                        ->with('title', 'Settings')
                        ->with('settings', $settings);
    }

    /**
     * Store a newly created resource in storage.
     * ...but in our case are we are updating a whole load of settings this is really the 'update' action.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

}