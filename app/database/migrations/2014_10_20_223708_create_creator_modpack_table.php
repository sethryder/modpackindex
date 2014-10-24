<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreatorModpackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('creator_modpack', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('creator_id')->unsigned()->index();
			$table->foreign('creator_id')->references('id')->on('creators')->onDelete('cascade');
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
		Schema::drop('creator_modpack');
	}

}
