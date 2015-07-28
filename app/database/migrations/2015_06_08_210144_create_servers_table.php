<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('servers', function ($table) {
            $table->increments('id');
            $table->integer('modpack_id');
            $table->integer('minecraft_version_id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('ip_host');
            $table->integer('port');
            $table->string('country', 3);
            $table->tinyInteger('permissions');
            $table->string('deck')->nullable();
            $table->string('website')->nullable();
            $table->string('application_url')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('server_address_hide')->default(false);
            $table->boolean('player_list_hide')->default(false);
            $table->boolean('motd_hide')->default(false);
            $table->boolean('email_alerts')->default(false);
            $table->boolean('active')->default(false);
            $table->boolean('queued')->default(false);
            $table->string('last_ip', 40)->nullable();
            $table->string('slug')->index();
            $table->timestamp('last_check');
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
        Schema::drop('servers');
    }

}
