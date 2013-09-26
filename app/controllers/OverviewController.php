<?php

class OverviewController extends BaseController
{

    function __construct()
    {
        $this->beforeFilter('auth.basic');
        $this->beforeFilter('csrf', array('on' => 'store'));
    }

    public function index()
    {
        $version = new Setting;
        return View::make('overview')
                        ->with('title', 'Overview'); // Customise the HTML page title per controller 'action'.
    }

}

?>
