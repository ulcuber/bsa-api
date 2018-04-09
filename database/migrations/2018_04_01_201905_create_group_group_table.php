<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_group', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('groups')->onDelete('cascade');
            $table->integer('child_id')->unsigned();
            $table->foreign('child_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_group');
    }
}
