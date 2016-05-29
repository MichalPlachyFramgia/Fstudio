<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version_name');
            $table->string('version_code');
            $table->datetime('publish_at');
            $table->integer('weight');
            $table->integer('applications_id');
            $table->string('update_title');
            $table->string('update_message');
            $table->boolean('update_type');
            $table->string('package_name');
            $table->string('direct_url');
            $table->string('sdk_version');
            $table->boolean('status');
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
        Schema::drop('application_versions');
    }
}
