<?php

use Illuminate\Database\Migrations\Migration;

class Initialschema extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function($table) {
                    $table->increments('id');
                    $table->string('username')->unique();
                    $table->string('password');
                    $table->string('email');
                    $table->timestamps();
                });

        Schema::create('settings', function($table) {
                    $table->string('name')->unique();
                    $table->text('svalue');
                    $table->string('friendlyname'); // A friendly setting name eg. 'Software Version'
                    $table->text('description'); // We'll use this to set friend user infomation. eg. 'This setting contains the current version of the Turbine software'.
                    $table->boolean('usersetting')->default(true); // Allow the user to edit via. the 'Settings' screen?
                    $table->primary('name');
                    $table->timestamps();
                });

        Schema::create('rules', function($table) {
                    $table->increments('id');
                    $table->string('hostheader')->unique();
                    $table->boolean('enabled')->default(true); // Is the rule enabled?
                    $table->boolean('nlb')->default(false); // Network Load-balanced?
                    $table->timestamps();
                    $table->softDeletes();
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('rules');
    }

}