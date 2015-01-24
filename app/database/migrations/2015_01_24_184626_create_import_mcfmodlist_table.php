<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportMcfmodlistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import_mcfmodlist', function ($table) {
			$table->increments('id');
			$table->integer('status');
			$table->string('name')->unique()->index();
			$table->text('description')->nullable();
			$table->string('url')->nullable();
			$table->string('source')->nullable();
			$table->string('raw_minecraft_versions')->nullable();
			$table->text('raw_authors')->nullable();
			$table->string('raw_dependencies')->nullable();
			$table->string('raw_type')->nullable();
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
		Schema::drop('import_mcfmodlist');
	}

}
