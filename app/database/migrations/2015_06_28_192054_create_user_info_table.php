<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('users_info', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('real_name')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('github')->nullable();
            $table->longText('about_me')->nullable();
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
        Schema::drop('users_info');
    }

}
