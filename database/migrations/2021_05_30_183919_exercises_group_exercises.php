<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExercisesGroupExercises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises_group_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_group_id')->index('exercise_group_id');
            $table->unsignedBigInteger('exercise_id')->index('exercise_id');
            $table->integer('approaches');
            $table->integer('repetition');
            $table->float('weight');
            $table->integer('order_ege');

            $table->foreign('exercise_group_id')
                ->references('id')
                ->on('exercises_groups');

            $table->foreign('exercise_id')
                ->references('id')
                ->on('exercises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises_group_exercises');
    }
}
