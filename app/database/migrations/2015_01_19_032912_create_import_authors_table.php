<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportAuthorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import_authors', function ($table) {
			$table->increments('id');
			$table->integer('import_id');
			$table->integer('author_alias_id')->nullable();
			$table->integer('status')->default(0);
			$table->string('name');
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
		Schema::drop('import_authors');
	}

}
