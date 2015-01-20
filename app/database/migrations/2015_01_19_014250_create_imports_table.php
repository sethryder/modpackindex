<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('imports', function ($table) {
			$table->increments('id');
			$table->integer('type');
			$table->integer('status');
			$table->string('name')->unique();
			$table->string('description')->nullable();
			$table->string('minecraft_version')->nullable();
			$table->text('raw_authors')->nullable();
			$table->string('url')->nullable();
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
		Schema::drop('imports');
	}

}
