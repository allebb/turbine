<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('SettingsTableSeeder');
        $this->command->info('Settings table seeded!');

        $this->call('RulesTableSeeder');
        $this->command->info('Rules table seeded!');
    }

}