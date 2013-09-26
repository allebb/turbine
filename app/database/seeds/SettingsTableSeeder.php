<?php

class SettingsTableSeeder extends Illuminate\Database\Seeder
{

    public function run()
    {
        // Version of Turbine Appliance software.
        $setting = new Setting(array(
            'name' => 'version',
            'svalue' => '0.1 alpha',
        ));
        $setting->save();
        // Default max_fails
        $setting = new Setting(array(
            'name' => 'maxfails',
            'svalue' => '1',
        ));
        $setting->save();
        // Fail timeout (in seconds)
        $setting = new Setting(array(
            'name' => 'failtimeout',
            'svalue' => '30',
        ));
        $setting->save();
    }

}

?>
