<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('news', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('contents')->nullable();
            $table->string('slug')->index();
            $table->timestamp('publish_at');
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
        Schema::drop('news');
	}

}
