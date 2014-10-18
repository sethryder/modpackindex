<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModModpackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mod_modpack', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('mod_id')->unsigned()->index();
			$table->foreign('mod_id')->references('id')->on('mods')->onDelete('cascade');
			$table->integer('modpack_id')->unsigned()->index();
			$table->foreign('modpack_id')->references('id')->on('modpacks')->onDelete('cascade');
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
		Schema::drop('mod_modpack');
	}

}
