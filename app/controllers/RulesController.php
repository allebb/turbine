<?php

use \Rule;
use \Input;

//use Turbine\NginxConfig;

class RulesController extends \BaseController
{

    function __construct()
    {
        $this->beforeFilter('auth.basic');
        $this->beforeFilter('csrf', array('on' => array('update')));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $rules = Rule::all();
        return View::make('rules')
                        ->with('title', 'Rules') // Customise the HTML page title per controller 'action'.
                        ->with('rules', $rules);
    }

    public function store()
    {
        $create_rule = new Rule;
        $create_rule->hostheader = strtolower(Input::get('origin_address'));
        //$create_rule->target_address = strtolower(Input::get('target_address')); // This will go directly into the nginx config file(s)
        $create_rule->enabled = Input::get('enabled');
        $create_rule->nlb = false;
        $create_rule->save();
        return Redirect::back()
                        ->with('flash_success', 'New rule for ' . $create_rule->hostheader . ' has been added successfully!');
    }

    /**
     * Lets display the edit form... GET /rules/{id}/edit
     */
    public function edit($id)
    {
        $rule = Rule::find($id);
        $config = new NginxConfig();
        //$config->setListenPort(80);
        $config->setHostheaders('www.example.com');

        $config->addServerToNLB(
                array(
                    '172.25.87.87:80', array(
                        'weight' => '1',
                        'max_fails' => '8',
                        'fail_timeout' => '30')
        ));
        $config->addServerToNLB(
                array(
                    '172.25.87.2:8081', array(
                        'weight' => '2',
                        'max_fails' => '1',
                        'fail_timeout' => '10')
        ));
        var_dump($config->writeConfig());
        return View::make('rules.edit')
                        ->with('title', 'Rules') // Customise the HTML page title per controller 'action'.
                        ->with('record', $rule);
    }

    /**
     * Lets store the changes and manipulate config files etc. PUT/PATCH /rules/{id}
     */
    public function update($id)
    {
        $update_rule = Rule::find($id);
        $update_rule->hostheader = strtolower(Input::get('origin_address'));
        //$create_rule->target_address = strtolower(Input::get('target_address')); // This will go directly into the nginx config file(s)
        $update_rule->enabled = Input::get('enabled');
        //$update_rule->nlb = false;
        $update_rule->save();
        return Redirect::back()
                        ->with('flash_info', 'New rule for ' . $update_rule->hostheader . ' has been updated successfully!');
    }

}