<?php

namespace api;

use Illuminate\Support\Facades\Response;

class ApiController extends \BaseController
{

    public function __construct()
    {
        $this->beforeFilter('auth.api');
    }

    // This controller is purely here for possible future use!

    public function missingMethod($parameters)
    {
        return Response::json(array(
                    'error' => true,
                    'message' => 'Invalid API request'
                        ), 405);
    }

}

?>
