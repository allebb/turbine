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
                });

        Schema::create('settings', function($table) {
                    $table->string('name')->unique();
                    $table->text('value');
                    $table->primary('name');
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
    }

}