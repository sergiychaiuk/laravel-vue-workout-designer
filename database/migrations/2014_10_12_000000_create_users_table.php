<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('role')->default(1);
            $table->string('sex')->nullable();
            $table->float('active')->nullable();
            $table->float('goal')->nullable();
            $table->integer('age')->default(0);
            $table->float('weight')->default(0);
            $table->float('height')->default(0);
            $table->float('neck')->default(0);
            $table->float('biceps')->default(0);
            $table->float('breast')->default(0);
            $table->float('waist')->default(0);
            $table->float('pelvis')->default(0);
            $table->float('shins')->default(0);
            $table->float('tightened')->default(0);
            $table->float('forearm')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
