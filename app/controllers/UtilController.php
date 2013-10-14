<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use \NginxConfig;
use \Rule;

class UtilController extends \BaseController
{

    function __construct()
    {
        // Require that the user is logged in!
        $this->beforeFilter('auth.basic');
        // We want to enable CSFR protection on both of our methods here!
        $this->beforeFilter('csrf', array('on' => array('postAddTarget')));
    }

    /**
     * Handles the deletion of proxy/nlb target servers.
     * @param int $id The rule ID of which to remove the target server address from.
     * @param string $target_hash An MD5 hash of the target server address.
     */
    public function getDeleteTarget($id, $target_hash)
    {
        // We now load in the target's nginx host file and delete the target that's name matches the md5 hash.
        $rule = Rule::find($id);
        if ($rule) {
            $config = new NginxConfig();
            $config->setHostheaders($rule->hostheader);
            $config->readConfig(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
            $existing_targets = json_decode($config->writeConfig()->toJSON());
            // We now iterate over each of the servers in the config file until we match the 'servers' name with the hash and when we do
            // we delete the host from the configuration file before writing the chagnes to disk...
            foreach ($existing_targets->nlb_servers as $target) {
                if (md5($target->target) == $target_hash) {
                    // Matches the target hash, we will now remove from the config file and break out the foreach.
                    $config->removeServerFromNLB($target->target);
                    break;
                }
            }
            $config->writeConfig()->toFile(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
            $config->reloadConfig();
        }
        return Redirect::back()
                        ->with('flash_success', 'Target ' . strtolower(Input::get('target')) . ' has been successfully deleted from this rule!');
    }

    /**
     * Handles adding a new target to an existing rule.
     * @param int $id The ID of the rule of which to add the new target too.
     */
    public function postAddTarget($id)
    {
        // we'll first validate the data before continueing...
        $validator = Validator::make(
                        array(
                    'target address' => Input::get('target'),
                    'max fails' => Input::get('maxfails'),
                    'fail timeout' => Input::get('failtimeout'),
                    'weight' => Input::get('weight'))
                        , array(
                    'target address' => array('required'),
                    'max fails' => array('integer', 'required'),
                    'fail timeout' => array('alpha_num', 'required'),
                    'weight' => array('numeric', 'between:1, 100'),
                        )
        );

        if ($validator->passes()) {
            $rule = Rule::find($id);
            if ($rule) {
                $config = new NginxConfig();
                $config->setHostheaders($rule->hostheader);
                $config->readConfig(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
                $config->addServerToNLB(array(
                    strtolower(Input::get('target')), array(
                        'max_fails' => Input::get('maxfails'),
                        'fail_timeout' => Input::get('failtimeout'),
                        'weight' => Input::get('weight'),
                    )
                ));
                $config->writeConfig()->toFile(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
                $config->reloadConfig();
            }
            return Redirect::back()
                            ->with('flash_success', 'New target ' . strtolower(Input::get('target')) . ' has been added!');
        } else {
            $errors = $validator->messages();
            return Redirect::back()
                            ->withInput()
                            ->with('flash_error', 'The following validation errors occured, please correct them and try again:<br /><br /> * ' . implode('<br /> * ', $errors->all()));
        }
    }

    /**
     * Logout the user...
     * Although we're using HTTP BASIC auth for Turbine we'll attempt to kill all sessions and then advise the user
     * to close their browser to complete the logout process!
     * @return View
     */
    public function getLogout()
    {
        Auth::logout();
        return View::make('logout.index')->with('title', 'Logging out...');
    }

}

?>
