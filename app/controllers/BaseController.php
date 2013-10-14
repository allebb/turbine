<?php

use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Controller;

class BaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    public function missingMethod($parameters)
    {
        // I'll change this later to ensure that a nice page is shown instead!
        die('Missing method dude!');
    }

}