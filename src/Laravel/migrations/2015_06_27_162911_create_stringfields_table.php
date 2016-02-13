<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStringfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stringfields', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('block_name');
            $table->integer('group_id');
            $table->string('group_name');
            $table->string('name');
            //$table->string('usedas'); //subtitle, citsign, link, slogan, echophrase, appeal, phrase (если не понятно что это обзывать phrase)

            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stringfields');
    }
}
