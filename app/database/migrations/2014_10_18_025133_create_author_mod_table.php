<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorModTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('author_mod', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('author_id')->unsigned()->index();
			$table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
			$table->integer('mod_id')->unsigned()->index();
			$table->foreign('mod_id')->references('id')->on('mods')->onDelete('cascade');
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
		Schema::drop('author_mod');
	}

}
