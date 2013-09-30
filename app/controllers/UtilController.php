<?php

class UtilController extends \BaseController
{

    public function getDeleteTarget($id, $target_hash)
    {
        // We now load in the target's nginx host file and delete the target that's name matches the md5 hash.
    }

    public function postAddTarget($id)
    {
        // The post data will enable us to add the new target
        //$target, $max_fails, $fail_timeout, $weight
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
        }
        return Redirect::back()
                        ->with('flash_success', 'New target ' . strtolower(Input::get('target')) . ' has been added!');
    }

}

?>
