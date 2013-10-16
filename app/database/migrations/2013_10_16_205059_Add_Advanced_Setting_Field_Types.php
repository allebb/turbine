<?php

use Illuminate\Database\Migrations\Migration;

class AddAdvancedSettingFieldTypes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function($table) {
                    $table->enum('type', array('textbox', 'dropdown', 'checkbox'))->default('textbox');
                    $table->string('options')->nullable()->default(null);
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function($table) {
                    $table->dropColumn('type', 'options');
                });
    }

}