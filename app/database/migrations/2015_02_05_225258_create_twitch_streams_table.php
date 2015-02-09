<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitchStreamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('twitch_streams', function ($table) {
			$table->integer('channel_id')->index()->unique();
			$table->integer('modpack_id')->index()->nullable();
			$table->integer('online');
			$table->string('status');
			$table->string('display_name');
			$table->string('language');
			$table->string('preview');
			$table->string('url');
			$table->integer('viewers');
			$table->integer('followers');
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
		Schema::drop('twitch_streams');
	}

}
