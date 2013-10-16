<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \Setting;

class GenerateKey extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'turbine:generatekey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and sets a new API key';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $keyset = Setting::where('name', 'api_key')->first();
        $keyset->svalue = md5(microtime());
        if ($keyset->save()) {
            $this->info('New API key was generated and set!');
        } else {
            $this->error('Unable to save new key, please try again!');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
                //	array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
                //	array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}