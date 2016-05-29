<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('messages_id');
            $table->string('title');
            $table->string('content');
            $table->string('icon');
            $table->integer('type');
            $table->string('package_name');
            $table->string('direct_url');
            $table->integer('success');
            $table->integer('failed');
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
        Schema::drop('message_histories');
    }
}
