<?php

class RulesController extends \BaseController
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
        return View::make('rules')
                        ->with('title', 'Rules'); // Customise the HTML page title per controller 'action'.
    }

}