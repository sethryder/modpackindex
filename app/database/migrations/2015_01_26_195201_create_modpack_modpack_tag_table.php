<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModpackModpackTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('modpack_modpack_tag', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('modpack_id')->unsigned()->index();
			$table->foreign('modpack_id')->references('id')->on('modpacks')->onDelete('cascade');
			$table->integer('modpack_tag_id')->unsigned()->index();
			$table->foreign('modpack_tag_id')->references('id')->on('modpack_tags')->onDelete('cascade');
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
		Schema::drop('modpack_modpack_tag');
	}

}
