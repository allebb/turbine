<?php

class OverviewController extends BaseController
{

    function __construct()
    {
        $this->beforeFilter('auth.basic');
    }

    /**
     * Lets just display some standard infomation about the server.
     */
    public function index()
    {

        // Lets get the total number of rules from the DB for the info splash.
        $total_rules = Rule::all()->count();
        // Lets get the total number of NLB rules from the DB for the info splash.
        $total_nlb_rules = Rule::where('nlb', true)->count();
        // Lets get the OS version from phpSysInfo and cache it (for performance)
        $os = 'Ubuntu Server 12.04 LTS';
        return View::make('overview')
                        ->with('title', 'Overview') // Customise the HTML page title per controller 'action'.
                        ->with('rulestotal', $total_rules)
                        ->with('rulesnlbtotal', $total_nlb_rules)
                        ->with('os', $os);
    }

}

?>
