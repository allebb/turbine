<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FactoryResetCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'turbine:factoryreset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores the local DB back to default settings.';

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
        if ($this->confirm('Are you sure you wish to restore to factory defaults? [y/N] ', false)) {
            // We'll execute migrations here, we'll reset and then re-migrate!
            $this->info('Database settings restored!');
            $this->info('Done!');
        } else {
            $this->error('User cancelled!');
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
                //array('example', InputArgument::REQUIRED, 'An example argument.'),
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
                //rearray('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}