<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServerWorldResetFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('servers', function($table)
		{
			$table->date('last_world_reset')->after('slug')->nullable();;
			$table->date('next_world_reset')->after('last_world_reset')->nullable();;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('servers', function($table)
		{
			$table->dropColumn('last_world_reset');
			$table->dropColumn('next_world_reset');
		});
	}

}
