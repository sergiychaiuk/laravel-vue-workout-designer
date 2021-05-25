<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesMuscles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises_muscles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_id')->index('exercise_id');
            $table->unsignedBigInteger('muscle_id')->index('muscle_id');

            $table->foreign('exercise_id')
                ->references('id')
                ->on('exercises');

            $table->foreign('muscle_id')
                ->references('id')
                ->on('muscles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises_muscles');
    }
}
