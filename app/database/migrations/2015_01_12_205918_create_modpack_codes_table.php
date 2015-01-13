<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModpackCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('modpack_codes', function ($table) {
			$table->increments('id');
			$table->integer('modpack_id');
			$table->integer('launcher_id');
			$table->string('code');
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
		Schema::drop('modpack_codes');
	}

}
