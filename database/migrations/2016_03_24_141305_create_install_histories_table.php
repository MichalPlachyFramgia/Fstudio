<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('install_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('devices_id');
            $table->integer('applications_id');
            $table->string('country');
            $table->string('install_from');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('install_histories');
    }
}
