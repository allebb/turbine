<?php

class SettingsTableSeeder extends Illuminate\Database\Seeder
{

    public function run()
    {
        // A whole load of useful settings for Nginx can be found here: http://wiki.nginx.org/HttpUpstreamModule
        // Version of Turbine Appliance software.
        $setting = new Setting(array(
            'name' => 'version',
            'svalue' => '0.1 alpha',
            'friendlyname' => 'Application version',
            'description' => 'Stores the current software version of the Turbine application.',
            'usersetting' => false,
        ));
        $setting->save();

        // Default max_fails
        $setting = new Setting(array(
            'name' => 'maxfails',
            'svalue' => '1',
            'friendlyname' => 'Max fails',
            'description' => 'The number of unsuccessful attempts at communicating with the backend server within the time period (assigned by parameter fail_timeout) after which it is considered inoperative. If not set, the number of attempts is one.',
            'usersetting' => true,
        ));
        $setting->save();

        // Fail timeout (in seconds)
        $setting = new Setting(array(
            'name' => 'failtimeout',
            'svalue' => '30',
            'friendlyname' => 'Fail timeout (in seconds)',
            'description' => 'The time (in seconds) during which must occur *max_fails* number of unsuccessful attempts at communication with the backend server that would cause the server to be considered inoperative, and also the time for which the server will be considered inoperative (before another attempt is made). If not set the time is 10 seconds. fail_timeout has nothing to do with upstream response time, use proxy_connect_timeout and proxy_read_timeout for controlling this.',
            'usersetting' => true,
        ));
        $setting->save();
    }

}

?>
