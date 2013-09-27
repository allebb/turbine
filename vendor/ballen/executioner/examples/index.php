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
require_once '../src/Ballen/Executioner/Executer.php';

use Ballen\Executioner\Executer;

$runner = new Executer();

$runner->setApplication('php') // Call the PHP executable to display the version infomation.
        ->addArgument('-v') // Displays the PHP version number!
        #->asExec()
        ->execute();

echo '<pre>' .$runner->resultAsText().'</pre><br/><br/>';

var_dump($runner->resultAsArray());

#echo $runner->resultAsJSON();

print_r($runner->getErrors());
?>
