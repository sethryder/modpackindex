<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('mods', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('deck')->nullable();
            $table->string('website')->nullable();
            $table->string('download_link')->nullable();
            $table->string('donate_link')->nullable();
            $table->string('wiki_link')->nullable();
            $table->longText('description')->nullable();
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
        Schema::drop('mods');
	}

}
