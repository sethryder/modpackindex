<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users', function($table)
        {
            $table->increments('id');
            $table->string('username', 16)->unique();
            $table->string('password', 128);
            $table->string('email')->index()->unique();
            $table->string('confirmation');
            $table->boolean('is_confirmed');
            $table->boolean('is_active');
            $table->boolean('is_admin');
            $table->boolean('is_moderator');
            $table->boolean('is_deleted');
            $table->string('register_ip', 40);
            $table->string('last_ip', 40);
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
        Schema::drop('users');
	}

}
