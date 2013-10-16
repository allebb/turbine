<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Log;
use \User;

class AdminResetCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'turbine:adminreset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores the default \'admin\' account and password';

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
        if ($this->confirm('Are you sure you wish to reset the \'admin\' account password? [y/N] ', false)) {
            // We'll execute migrations here, we'll reset and then re-migrate!
            $user = User::where('username', 'admin')->first();
            if ($user) {
                $user->password = Hash::make('password');
                $user->save();
                // We'll log for security reasons to the log that the password was reset via. the console.
                Log::info('The \'admin\' account password was reset via. the console.');
                $this->info('The \'admin\' password has been successfully reset to \'password\'!');
            } else {
                $this->error('No \'admin\' account was found!');
            }
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
                //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}