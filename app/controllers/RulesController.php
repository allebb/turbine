<?php

use \Rule;
use \Input;

class RulesController extends \BaseController
{

    function __construct()
    {
// Require that the user is logged in!
        $this->beforeFilter('auth.basic');
// We want to enable CSFR protection on both the 'update', 'store' and 'destroy' actions.
        $this->beforeFilter('csrf', array('on' => array('update', 'store', 'destroy')));
    }

    /**
     * Displays current rules and their target servers etc.
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

    /**
     * Handles the creation of a new rule. (POST /rule)
     * @return type
     */
    public function store()
    {
// we'll first validate the data before continueing...
        $validator = Validator::make(
                        array(
                    'origin address' => Input::get('origin_address'),
                    'target address' => Input::get('target_address'),
                        )
                        , array(
                    'origin address' => array('required', 'unique:rules,hostheader'),
                    'target address' => array('required'),
                        )
        );

        if ($validator->passes()) {
            $create_rule = new Rule;
            $create_rule->hostheader = strtolower(Input::get('origin_address'));
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
                $config->reloadConfig();
            }

            return Redirect::back()
                            ->with('flash_success', 'The rule for ' . $create_rule->hostheader . ' has been added successfully!');
        } else {
            $errors = $validator->messages();
            return Redirect::back()
                            ->withInput()
                            ->with('flash_error', 'The following validation errors occured, please correct them and try again:<br /><br /> * ' . implode('<br /> * ', $errors->all()));
        }
    }

    /**
     * Display the 'edit' rule form (GET /rules/{id}/edit)
     * @param int $id The ID of the rule of which is to be edited.
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
        return View::make('rules.edit')
                        ->with('title', 'Edit rules') // Customise the HTML page title per controller 'action'.
                        ->with('record', $rule)
                        ->with('targets', json_decode($targets));
    }

    /**
     * Stores the changes and manipulate config files etc. (PUT/PATCH /rules/{id})
     * @param int $id The ID of the rule of which to update.
     */
    public function update($id)
    {

        // We add validation here to check that data is valid before updating the records and config file!
        $validator = Validator::make(
                        array(
                    'origin address' => Input::get('origin_address'),
                        )
                        , array(
                    'origin address' => array('required'),
                        )
        );

        if ($validator->passes()) {

            $update_rule = Rule::find($id);
            if ($update_rule) {

                // We need to check if the origin address has changed and if so rename (move)
                // the configuration file first and then proceed to save the changes!
                if (strtolower(Input::get('origin_address')) != $update_rule->hostheader) {
                    $config_object = new NginxConfig();
                    $new_config_object = new NginxConfig();
                    $config_file = Setting::getSetting('nginxconfpath') . '/' . $config_object->setHostheaders(strtolower(Input::get('origin_address')))->serverNameToFileName() . '.enabled.conf';
                    rename(Setting::getSetting('nginxconfpath') . '/' . $new_config_object->setHostheaders(strtolower($update_rule->hostheader))->serverNameToFileName() . '.enabled.conf', $config_file);
                } else {
                    $config_object = new NginxConfig();
                    $config_file = Setting::getSetting('nginxconfpath') . '/' . $config_object->setHostheaders(strtolower($update_rule->hostheader))->serverNameToFileName() . '.enabled.conf';
                }

                // We now laod in the configuration file.
                $existing_config = new NginxConfig();
                $existing_config->setHostheaders($update_rule->hostheader);
                $existing_config->readConfig($config_file);
                $targets = json_decode($existing_config->writeConfig()->toJSON());

                $update_rule->hostheader = strtolower(Input::get('origin_address'));
                //$update_rule->enabled = Input::get('enabled'); - Will add this as a feature in later versions of the software!
                if ($update_rule->save()) {
                    $no_targets = 0;
                    // Lets grab each of the targets and iterate through the form changes to update each targets details:-
                    foreach ($targets->nlb_servers as $target) {

                        $target_hash = md5($target->target);
                        $existing_config->removeServerFromNLB($target->target);
                        $existing_config->addServerToNLB(array(
                            strtolower(Input::get('target_' . $target_hash)),
                            array(
                                'max_fails' => Input::get('maxfails_' . $target_hash),
                                'fail_timeout' => Input::get('failtimeout_' . $target_hash),
                                'weight' => Input::get('weight_' . $target_hash),
                            )
                        ));
                        $no_targets++;
                    }
                    $existing_config->writeConfig()->toFile(Setting::getSetting('nginxconfpath') . '/' . $existing_config->serverNameToFileName() . '.enabled.conf');
                }

                if ($no_targets > 1) {
                    // We now update record to indicate that it is a NLB setup if there are more than one target associated to the rule!
                    $update_rule->nlb = true;
                    $update_rule->save();
                }
                // We now reload the configuration file to ensure changes take immediate affect.
                $existing_config->reloadConfig();
            }
            return Redirect::back()
                            ->with('flash_info', 'The settings and targets for ' . $update_rule->hostheader . ' has been updated successfully!');
        } else {
            $errors = $validator->messages();
            return Redirect::back()
                            ->withInput()
                            ->with('flash_error', 'The following validation errors occured, please correct them and try again:<br /><br /> * ' . implode('<br /> * ', $errors->all()));
        }
    }

    /**
     * This handles the deletion of the deletion of the rule. (DELETE /rules/{id})
     * @param int $id The ID of the rule to delete.
     */
    public function destroy($id)
    {
// Lets delete the record from the DB..
        $delete_rule = Rule::find($id);
        if ($delete_rule) {
// Delete the config file.
            $config = new NginxConfig();
            $config->setHostheaders($delete_rule->hostheader);
            $config->readConfig(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
            if ($config->deleteConfig(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf')) {
// We now delete the record from the DB...
                $delete_rule->delete();
            }
            $config->reloadConfig();
        }
        return Redirect::route('rules.index')
                        ->with('flash_success', 'The rule for ' . json_decode($config->toJSON())->server_name . ' has been deleted successfully!');
    }

}