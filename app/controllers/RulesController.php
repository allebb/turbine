<?php

use \Rule;
use \Input;
use Ballen\Executioner\Executer;

class RulesController extends \BaseController
{

    function __construct()
    {
        $this->beforeFilter('auth.basic');
        //$this->beforeFilter('csrf', array('on' => array('update')));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $rules = Rule::all();

        // Lets load in each of the rules and get the target infomation for each..
        $combined_list = array();
        foreach ($rules as $rule) {
            $combined = array();

            // Now we load in each config file...
            $reader = new NginxConfig();
            $reader->setHostheaders($rule->hostheader);
            $reader->readConfig(Setting::getSetting('nginxconfpath') . '/' . $reader->serverNameToFileName() . '.enabled.conf');
            $targets = $reader->writeConfig()->toJSON();
            $combined['hostheader'] = $rule->hostheader;
            $target_array = json_decode($targets, true);
            $target_string = '';
            $total_hosts = 0;
            foreach ($target_array['nlb_servers'] as $single_target) {
                $target_string = $target_string . $single_target['target'] . '<br />';
                $total_hosts++;
            }
            $combined['id'] = $rule->id;
            $combined['targets'] = $target_string;
            $combined['enabled'] = true;
            if ($total_hosts > 1) {
                $combined['nlb'] = true;
            } else {
                $combined['nlb'] = false;
            }
            $combined_list[] = $combined;
        }

        return View::make('rules')
                        ->with('title', 'Rules') // Customise the HTML page title per controller 'action'.
                        ->with('total_rules', $rules->count())
                        ->with('rules', json_decode(json_encode($combined_list)));
    }

    public function store()
    {
        $create_rule = new Rule;
        $create_rule->hostheader = strtolower(Input::get('origin_address'));
        //$create_rule->target_address = strtolower(Input::get('target_address')); // This will go directly into the nginx config file(s)
        $create_rule->enabled = Input::get('enabled');
        $create_rule->nlb = false;
        if ($create_rule->save()) {
            // We now write out the configuration file for the nginx virtual host.
            $config = new NginxConfig();
            $config->setHostheaders($create_rule->hostheader);
            $config->addServerToNLB(array(
                strtolower(Input::get('target_address')),
                array(
                    'weight' => 1,
                    'max_fails' => Setting::getSetting('maxfails'),
                    'fail_timeout' => Setting::getSetting('failtimeout'),
                )
            ));
            $config->writeConfig();
            $config->toFile(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');

            $service_reloader = new Executer;
            $service_reloader->setApplication('service')->addArgument('nginx reload');
            $service_reloader->execute();
            //die(var_dump($service_reloader->resultAsArray()));
        }

        return Redirect::back()
                        ->with('flash_success', 'The rule for ' . $create_rule->hostheader . ' has been added successfully!');
    }

    /**
     * Lets display the edit form... GET /rules/{id}/edit
     */
    public function edit($id)
    {
        $rule = Rule::find($id);

        if ($rule) {
            // Load in the current configuration
            $config = new NginxConfig();
            $config->setHostheaders($rule->hostheader);
            $config->readConfig(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
            $targets = $config->writeConfig()->toJSON();
        }

        //die(var_dump($targets));
        return View::make('rules.edit')
                        ->with('title', 'Edit rules') // Customise the HTML page title per controller 'action'.
                        ->with('record', $rule)
                        ->with('targets', json_decode($targets));
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