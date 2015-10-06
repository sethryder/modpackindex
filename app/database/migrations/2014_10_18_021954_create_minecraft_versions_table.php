<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinecraftVersionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('minecraft_versions', function ($table) {
            $table->increments('id');
            $table->string('name')->unique;
            $table->timestamps();
        });

		DB::table('minecraft_versions')->insert([
			['name' => '1.6.4'],
			['name' => '1.7.10'],
			['name' => '1.8']
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('minecraft_versions');
	}

}
