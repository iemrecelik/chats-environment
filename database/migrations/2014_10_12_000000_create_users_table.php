<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('nickname')->unique();
            $table->text('bio')->nullable();
            $table->text('brief')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->unique()->nullable();
            $table->boolean('hide_account')->nullable();
            $table->enum('gender', ['f', 'm'])->nullable();
            $table->smallInteger('online')->default(0);
            $table->string('language')->nullable();
            $table->integer('date_of_birth')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
