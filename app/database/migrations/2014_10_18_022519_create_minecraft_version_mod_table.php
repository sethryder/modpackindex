<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMinecraftVersionModTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('minecraft_version_mod', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('minecraft_version_id')->unsigned()->index();
			$table->foreign('minecraft_version_id')->references('id')->on('minecraft_versions')->onDelete('cascade');
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
		Schema::drop('minecraft_version_mod');
	}

}
