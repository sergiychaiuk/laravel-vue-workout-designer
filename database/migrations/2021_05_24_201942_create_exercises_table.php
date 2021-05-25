<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('muscle_group_id')->index('muscle_group_id');
            $table->string('image');
            $table->text('description');
            $table->text('starting_position');
            $table->text('execution_trajectory');
            $table->text('technique_execution');
            $table->unsignedBigInteger('sports_projectile_id')->index('sports_projectile_id');

            $table->foreign('muscle_group_id')
                ->references('id')
                ->on('muscle_groups')
                ->onDelete('cascade');

            $table->foreign('sports_projectile_id')
                ->references('id')
                ->on('sports_projectiles')
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
        Schema::dropIfExists('exercises');
    }
}
