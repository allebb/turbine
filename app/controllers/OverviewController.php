<?php

class OverviewController extends BaseController
{

    public function index()
    {
        return View::make('overview')
                        ->with('title', 'Overview'); // Customise the HTML page title per controller 'action'.
    }

}

?>
