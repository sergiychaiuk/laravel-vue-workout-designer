<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('muscles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('muscle_group_id')->index('muscle_group_id');
            $table->string('image');
            $table->text('description');
            $table->text('functions');

            $table->foreign('muscle_group_id')
                ->references('id')
                ->on('muscle_groups')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('muscles');
    }
}
