<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportNemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import_nem', function ($table) {
			$table->increments('id');
			$table->integer('status');
			$table->string('name')->unique()->index();
			$table->string('url')->nullable();
			$table->string('repo')->nullable();
			$table->string('mod_version')->nullable();
			$table->string('raw_minecraft_versions')->nullable();
			$table->text('raw_authors')->nullable();
			$table->text('raw_dependencies')->nullable();
			$table->string('nem_lastupdated')->nullable();
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
		Schema::drop('import_nem');
	}

}
