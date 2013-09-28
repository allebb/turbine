<?php

class UtilController extends \BaseController
{

    public function getDeleteTarget($id, $target_hash)
    {
        // We now load in the target's nginx host file and delete the target that's name matches the md5 hash.

    }

    public function postAddTarget($id){
        // The post data will enable us to add the new target
        //$target, $max_fails, $fail_timeout, $weight
    }

}

?>
