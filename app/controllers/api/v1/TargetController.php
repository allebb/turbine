<?php

namespace api\v1;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use \api\ApiController as ApiController;
use Illuminate\Support\Facades\Validator;
use \NginxConfig;
use \Setting;
use \Rule;

class TargetController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds a new target server to an existing rule.
     * @param int $id The rule id of which to attach the target too.
     * @return Response
     */
    public function update($id)
    {
        // We create a new target address.
        $validator = Validator::make(
                        array(
                    'rule id' => $id,
                    'target address' => Input::get('target'),
                    'max fails' => Input::get('maxfails'),
                    'fail timeout' => Input::get('failtimeout'),
                    'weight' => Input::get('weight'))
                        , array(
                    'rule id' => array('required'),
                    'target address' => array('required'),
                    'max fails' => array('integer', 'required'),
                    'fail timeout' => array('alpha_num', 'required'),
                    'weight' => array('numeric', 'between:1, 100'))
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
            return Response::json(array(
                        'error' => false,
                        'message' => 'Target created!'
                            )
                            , 201);
        } else {
            $errors = $validator->messages();
            return Response::json(array(
                        'errors' => true,
                        'message' => $errors->all())
                            , 400);
        }
    }

    /**
     * Removes a target server from a rule.
     * @param string $id The rule ID and md5 hash of the target address seperated by the
     * hyphen '-' character eg. 2-23a23e5605cc132c95b4902b7b3c0072 in the example, '2' is the
     * ID of the rule to remove the MD5 representation of the target name eg. md5('172.23.32.2')
     * @return Repsonse
     */
    public function destroy($id)
    {
        $idhash = explode('-', $id);
        $id = $idhash[0]; // The ID of the rule which the target is to be removed from.
        $hash = $idhash[1]; // The MD5 representation of the target address.

        $rule = Rule::find($id);
        if ($rule) {
            $config = new NginxConfig();
            $config->setHostheaders($rule->hostheader);
            $config->readConfig(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
            $existing_targets = json_decode($config->writeConfig()->toJSON());
            // We now iterate over each of the servers in the config file until we match the 'servers' name with the hash and when we do
            // we delete the host from the configuration file before writing the chagnes to disk...
            $deleted = false;
            foreach ($existing_targets->nlb_servers as $target) {
                if (md5($target->target) == $hash) {
                    // Matches the target hash, we will now remove from the config file and break out the foreach.
                    $config->removeServerFromNLB($target->target);
                    $deleted = true;
                    break;
                }
            }
            $config->writeConfig()->toFile(Setting::getSetting('nginxconfpath') . '/' . $config->serverNameToFileName() . '.enabled.conf');
            $config->reloadConfig();
            if ($deleted) {
                return Response::json(array(
                            'errors' => false,
                            'message' => 'The target was successfully remove from the rule.'), 200);
            } else {
                return Response::json(array(
                            'errors' => true,
                            'message' => 'The target server was not found in the configuration.'), 404);
            }
        } else {
            return Response::json(array(
                        'errors' => true,
                        'message' => 'The target parent rule was not found and therefore could not be removed.'), 404);
        }
    }

}

?>
