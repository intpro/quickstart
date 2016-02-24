<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug')->index();
            $table->integer('owner_id')->unsigned()->index();
            $table->string('group_owner_name')->index();
            $table->string('group_name')->index();

            $table->string('block_name')->index();
            $table->string('title');
            $table->integer('sorter');
            $table->boolean('show');

            $table->timestamps();
        });

//        Schema::create('group_group', function(Blueprint $table)
//        {
//            $table->integer('group_id')->unsigned()->index();
//            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
//
//            $table->integer('owner_id')->unsigned()->index();
//            $table->foreign('owner_id')->references('id')->on('groups')->onDelete('cascade');
//
//            $table->string('block_name');
//            $table->string('owner_name');
//            $table->string('group_name');
//
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('group_group');
        Schema::drop('groups');
    }
}
