<?php

class Setting extends \Eloquent
{

    protected $table = 'settings';

    /**
     * Return a system setting value from the database.
     * @param string $gname The system option name to return the value of.
     * @return string
     */
    public static function getSetting($gname)
    {
        $data = self::where('name', $gname)->first();
        return $data->svalue;
    }

    /**
     * Creates a new system setting and assigns a value to it.
     * @param string $name The name of the system setting to create.
     * @param string $value The value to assign to the new system setting.
     */
    public static function setSetting($name, $value)
    {

    }

    /**
     * Update the value of an existing system option.
     * @param type $name
     * @param type $value
     */
    public static function updateSetting($name, $value)
    {

    }

}

?>
