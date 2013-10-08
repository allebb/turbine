<?php

class Rule extends Eloquent
{

    protected $table = 'rules';

    /**
     * We validate the rules here before allowing the data to be added into the database.
     * @param array $input Array containing 'keys' and values for the validation.
     * @return boolean
     */
    public static function validate($input)
    {
        $rules = array(
            'origin' => array('unique:rules,hostheader', 'required'),
            'target' => array('required'),
        );
        return Validator::make($input, $rules);
    }

}

?>
