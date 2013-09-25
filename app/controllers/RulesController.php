<?php

class RulesController extends \BaseController
{

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