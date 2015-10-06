<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('server_users', function ($table) {
			$table->increments('id');
			$table->integer('server_id');
			$table->string('email');
			$table->string('edit_password', 128);
			$table->boolean('is_confirmed')->default(false);
			$table->string('last_ip', 40)->nullable();
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
		Schema::drop('server_users');
	}

}
