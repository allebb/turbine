<?php

namespace api;

use \Response;
use \Rule;
use \Input;

class RulesController extends ApiController
{

    public function __construct()
    {
        // Will requrire that the API authenticiation is reqired!
        parent::__construct();
    }

    /**
     * Retreieve a list of all the rules and rule targets.
     * @return Response
     */
    public function index()
    {
        $all_rules = Rule::all();
        return Response::json(array(
                    'error' => false,
                    'rules' => $all_rules->toArray()
                        ), 200);
    }

    /**
     * Retrieve an existing rule by it's database ID.
     * @param int $id The ID of the rule to return.
     * @return Response
     */
    public function show($id)
    {
        $rule = Rule::find($id);
        if ($rule)
            return Response::json(array(
                        'error' => false,
                        'rule' => $rule->toArray()
                            ), 200);
        return Response::json(array(
                    'error' => true,
                    'message' => 'The requested rule does not exist.'
                        ), 404);
    }

    /**
     * Create a new rule
     * @return Response
     */
    public final function store()
    {
        $validator = Rule::validate(Input::all());
        if ($validator->passes()) {
            return Response::json(array(
                        'error' => false,
                        'message' => 'Rule created',
                            ), 201);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => $validator->messages()->all()
                            ), 500);
        }
    }

    /**
     * Update an existing rule.
     * @param int $id The database ID of the rule to be updated.
     * @return Response
     */
    public function update($id)
    {
        return Response::json(array(
                    'error' => false,
                    'message' => 'Rule updated'
                        ), 200);
    }

    /**
     * Delete an existing rule by ID.
     * @param int $id The database ID of the rule to be deleted.
     * @return Response
     */
    public function destory($id)
    {
        return Response::json(array(
                    'error' => false,
                    'message' => 'Rule deleted'
                        ), 200);
    }

}

?>
