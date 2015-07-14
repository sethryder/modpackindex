<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('server_tags', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('deck')->nullable();
            $table->longText('description')->nullable();
            $table->string('last_ip', 40)->nullable();
            $table->string('slug')->index();
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
        Schema::drop('server_tags');
    }

}
