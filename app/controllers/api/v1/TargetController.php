<?php

namespace api\v1;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use \Rule;
use \api\ApiController as ApiController;

class TargetController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function store()
    {
        // We create a new target address.
        return Response::json(array(
                    'error' => false,
                    'message' => 'Target created!'
                        ), 201);
    }

    public function destroy($id)
    {
        // We delete the specified target here!
        return Response::json(array(
                    'error' => false,
                    'message' => 'The following rule target has been deleted for rule ID ' . $id
                        ), 200);
    }

}

?>
