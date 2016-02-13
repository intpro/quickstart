<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bools', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('block_name');
            $table->integer('group_id');
            $table->string('group_name');
            $table->string('name');

            $table->boolean('value');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bools');
    }
}
