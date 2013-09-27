<?php

use \Rule;
use \Input;

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

        // Example usage
        /**
          $config = new NginxConfig();
          $config->readConfig('C:/Users/alleb4/Desktop/example2.conf');
          $config->removeServerFromNLB('172.25.87.87:80');
          $config->addServerToNLB(array('172.25.87.99', array('weight' => '16')));
          $config->addServerToNLB(array('172.25.87.3', array('weight' => 8, 'max_fails' => 99)));
          $config->removeServerFromNLB('172.25.87.3');
          echo $config->setListenPort(80)
          ->setHostheaders()
          ->writeConfig()
          ->toSrceen(); // We can write it out to the screen..
          //->toFile('C:/Users/alleb4/Desktop/example2.conf'); // or to the file system!
          //->toJSON();
         */
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