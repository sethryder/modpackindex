<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('youtube', function ($table) {
			$table->increments('id');
			$table->string('type');
			$table->string('title');
			$table->string('modpack_id')->nullable();
			$table->string('mod_id')->nullable();
			$table->string('youtube_id')->nullable();
			$table->string('channel_id');
			$table->string('channel_title')->nullable();
			$table->string('thumbnail')->nullable();
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
		Schema::drop('youtube');
	}

}
