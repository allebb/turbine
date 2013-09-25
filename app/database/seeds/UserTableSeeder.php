<?php

class UserTableSeeder extends Illuminate\Database\Seeder
{

    public function run()
    {
        $adminuser = new User(array(
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email' => 'root@localhost',
        ));
        $adminuser->save();
    }

}
?>
