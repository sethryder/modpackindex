<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModRequirementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mod_requirements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('mod_id')->unsigned()->index();
			$table->foreign('mod_id')->references('id')->on('mods')->onDelete('cascade');
			$table->integer('required_mod_id')->unsigned()->index();
			$table->foreign('required_mod_id')->references('id')->on('mods')->onDelete('cascade');
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
		Schema::drop('mod_requirements');
	}

}
