<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textfields', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('block_name');
            $table->integer('group_id');
            $table->string('group_name');
            $table->string('name');
            //$table->string('usedas'); //lead, descr, remark, citation, postscriptum

            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('textfields');
    }
}
