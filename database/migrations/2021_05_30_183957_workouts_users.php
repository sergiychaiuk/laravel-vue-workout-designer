<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WorkoutsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workouts_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workout_id')->index('workout_id');
            $table->unsignedBigInteger('user_id')->index('user_id');

            $table->foreign('workout_id')
                ->references('id')
                ->on('workouts');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workouts_users');
    }
}
