<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeprecatedFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('modpacks', function($table)
		{
			$table->boolean('is_deprecated')->after('description')->default(false);
			$table->integer('sequel_modpack_id')->after('is_deprecated')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('modpacks', function(Blueprint $table)
		{
			$table->dropColumn('is_deprecated');
			$table->dropColumn('sequel_modpack_id');
		});
	}

}
