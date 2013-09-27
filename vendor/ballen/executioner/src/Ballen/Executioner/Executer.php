<?php

/**
 * Executioner Process Execution Library
 *
 * Executioner (executer) is a PHP library for executing system processes
 * and applications with the ability to pass extra arguments and read
 *  CLI output results.
 *
 * @author bobbyallen.uk@gmail.com (Bobby Allen)
 * @version 1.0.0
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/bobsta63/executioner
 *
 */

namespace Ballen\Executioner;

class Executer
{

    /**
     * The full system path to the application/process you want to exectute.
     * @var string Full system path to the application/process.
     */
    private $application_path;

    /**
     * Stores application arguments to be parsed with the application.
     * @var array Stores arguments to be sent with the command.
     */
    private $application_arguments = array();

    /**
     * Stores the CLI response.
     * @var array
     */
    private $exectuion_response = array();

    /**
     * Stores a list of errors (if applicable after applciation exection.)
     * @var array Stores a list of class error messages if applicable.
     */
    private $execution_errors = array();

    /**
     * Specifies the method of which to use to execute the applicaiton.
     * @var string Method used to execute the application.
     */
    private $run_method = 'exec';

    /**
     * Adds an argument to be added to the execution string.
     * @param string $argument Argument to be added.
     */
    public function addArgument($argument)
    {
        $this->application_arguments[] = $argument;
        return $this;
    }

    /**
     * Sets the application and path of which to be executed.
     * @param string $application The full system path to the application to execute.
     */
    public function setApplication($application)
    {
        $this->application_path = $application;
        return $this;
    }

    /**
     * Generates a list of arguments to be appended onto the executed path.
     * @return string The generated list of arguments.
     */
    protected function generateArguments()
    {
        $argument_line = (string) '';
        if (count($this->application_arguments) > 0) {
            foreach ($this->application_arguments as $argn) {
                $argument_line .= ' ' . $argn;
            }
        }
        return rtrim($argument_line);
    }

    public function asExec()
    {
        $this->run_method = 'exec';
        return $this;
    }

    public function asPassthru()
    {
        $this->run_method = 'passthru';
        return $this;
    }

    /**
     * Executes the appliaction with all of the added arguments.
     * @return type
     */
    public function execute()
    {
        $this->exectuion_response = null;
        $this->execution_errors = null;

        if (exec($this->application_path . $this->generateArguments(), $this->exectuion_response)) {
            return true;
        } else {
            $this->execution_errors[] = 'Unidentified error occured when attempting to exectute: ' . $this->application_path . $this->generateArguments();
            return false;
        }
    }

    /**
     * Checks if the application/process is executable.
     * @param type $file
     * @return boolean
     */
    protected function isExecutable()
    {
        if (is_executable($this->application_path))
            return true;
        return false;
    }

    /**
     * Returns an array of class error messages.
     * @return array Iteratable array of error messages.
     */
    public function getErrors()
    {
        return $this->execution_errors;
    }

    /**
     * Returns the result (stdout) as an array.
     * @return array Result text (STDOUT).
     */
    public function resultAsArray()
    {
        return $this->exectuion_response;
    }

    /**
     * Returns the result (stdout) as a JSON string.
     * @return string Result text (STDOUT).
     */
    public function resultAsJSON()
    {
        return json_encode($this->exectuion_response);
    }

    /**
     * Returns the result (stdout) as seralized data.
     * @return string Result text (STDOUT).
     */
    public function resultAsSerialized()
    {
        return serialize($this->exectuion_response);
    }

    /**
     * Returns the result (stdout) as a raw text string.
     * @return string Result text (STDOUT).
     */
    public function resultAsText()
    {
        $buffer = (string) '';
        foreach ($this->exectuion_response as $stdout) {
            $buffer .= $stdout . "\n";
        }
        return $buffer;
    }

}

?>
