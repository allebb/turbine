<?php

class SettingsTableSeeder extends Illuminate\Database\Seeder
{

    public function run()
    {
        // A whole load of useful settings for Nginx can be found here: http://wiki.nginx.org/HttpUpstreamModule
        // Version of Turbine Appliance software.
        $setting = new Setting(array(
            'name' => 'version',
            'svalue' => '1.0.0',
            'friendlyname' => 'Application version',
            'description' => 'Stores the current software version of the Turbine application.',
            'usersetting' => false,
        ));
        $setting->save();

        // Default max_fails
        $setting = new Setting(array(
            'name' => 'node_maxfails',
            'svalue' => '1',
            'friendlyname' => 'Default node max fails',
            'description' => 'The number of unsuccessful attempts at communicating with the backend server within the time period (assigned by parameter fail_timeout) after which it is considered inoperative. If not set, the number of attempts is one.',
            'usersetting' => true,
        ));
        $setting->save();

        // Fail timeout (in seconds)
        $setting = new Setting(array(
            'name' => 'node_failtimeout',
            'svalue' => '30',
            'friendlyname' => 'Default node fail timeout (in seconds)',
            'description' => 'The time (in seconds) during which must occur *max_fails* number of unsuccessful attempts at communication with the backend server that would cause the server to be considered inoperative, and also the time for which the server will be considered inoperative (before another attempt is made). If not set the time is 10 seconds. fail_timeout has nothing to do with upstream response time, use proxy_connect_timeout and proxy_read_timeout for controlling this.',
            'usersetting' => true,
        ));
        $setting->save();

        // Default node weight
        $setting = new Setting(array(
            'name' => 'node_weight',
            'svalue' => '1',
            'friendlyname' => 'Default node weight',
            'description' => 'The default node weight of the target server.',
            'usersetting' => true,
        ));
        $setting->save();

        // Path to the root of where the Nginx configs are save too.
        $setting = new Setting(array(
            'name' => 'nginxconfpath',
            'svalue' => '/etc/turbine/configs',
            'friendlyname' => 'Proxy configuration path',
            'description' => 'The root path to where the Nginx virtual host configurations are stored. (No trailing slash)',
            'usersetting' => true,
        ));
        $setting->save();

        // Enable the remote API or not.
        $setting = new Setting(array(
            'name' => 'api_enabled',
            'svalue' => 'false',
            'friendlyname' => 'API remote access ',
            'description' => 'Allow remote access to the Turbine RESTful API to add and ammend rules via. third-party applications.',
            'usersetting' => true,
            'type' => 'dropdown',
            'options' => 'false|true'
        ));
        $setting->save();

        // API key
        $setting = new Setting(array(
            'name' => 'api_key',
            'svalue' => '-- SET YOUR OWN API KEY HERE --',
            'friendlyname' => 'API auth key',
            'description' => 'Customise your own API key to prevent un-authorised access to the API.',
            'usersetting' => true,
        ));
        $setting->save();
    }

}

?>
