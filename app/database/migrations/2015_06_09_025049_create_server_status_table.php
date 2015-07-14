<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('server_status', function ($table) {
            $table->increments('id');
            $table->integer('server_id')->index();
            $table->integer('current_players')->default(0);
            $table->integer('max_players')->default(0);
            $table->text('players')->nullable;
            $table->text('mods')->nullable;
            $table->integer('failed_attempts')->default(0);
            $table->integer('failed_checks')->default(0);
            $table->integer('total_failed_checks')->default(0);
            $table->integer('total_checks')->default(0);
            $table->timestamp('last_check');
            $table->timestamp('last_success');
            $table->timestamp('last_failure');
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
        Schema::drop('server_status');
	}

}
