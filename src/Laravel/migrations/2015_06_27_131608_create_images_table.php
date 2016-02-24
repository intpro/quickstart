<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('value');//Для совместимости запросов

            $table->string('name');
            $table->string('block_name');
            $table->string('group_name');
            $table->integer('group_id');
            $table->string('prefix');
            $table->string('alt');
            $table->string('original_link');
            $table->string('primary_link');
            $table->string('secondary_link');
            $table->string('icon_link');
            $table->string('preview_link');
            $table->integer('cache_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}
