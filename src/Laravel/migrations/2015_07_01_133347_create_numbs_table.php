<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbs', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('block_name');
            $table->integer('group_id');
            $table->string('group_name');
            $table->string('name');

            $table->integer('value');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('numbs');
    }
}
