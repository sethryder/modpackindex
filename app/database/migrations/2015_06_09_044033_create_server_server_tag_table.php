<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServerServerTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('server_server_tag', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('server_id')->unsigned()->index();
			$table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
			$table->integer('server_tag_id')->unsigned()->index();
			$table->foreign('server_tag_id')->references('id')->on('server_tags')->onDelete('cascade');
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
		Schema::drop('server_server_tag');
	}

}
