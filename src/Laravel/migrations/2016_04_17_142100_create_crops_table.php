<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crops', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('value');//Для совместимости запросов

            $table->string('name');

            $table->string('image_name');
            $table->string('block_name');
            $table->string('group_name');
            $table->integer('group_id');
            $table->integer('image_id');
            $table->string('alt');
            $table->string('link');
            $table->string('man_sufix');
            $table->string('target_sufix');
            $table->integer('cache_index');
            $table->integer('man_x1');
            $table->integer('man_y1');
            $table->integer('man_x2');
            $table->integer('man_y2');
            $table->integer('target_x1');
            $table->integer('target_y1');
            $table->integer('target_x2');
            $table->integer('target_y2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('crops');
    }
}
