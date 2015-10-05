<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('permissions', function ($table) {
            $table->increments('id');
            $table->string('route');
            $table->string('display_name');
            $table->timestamps();
        });

        DB::table('permissions')->insert([
            ['route' => 'mod_add', 'display_name' => 'Add Mod'],
            ['route' => 'mod_edit', 'display_name' => 'Edit Mod'],
            ['route' => 'modpack_add', 'display_name' => 'Add Modpack'],
            ['route' => 'modpack_edit', 'display_name' => 'Edit Modpack'],
            ['route' => 'author_add', 'display_name' => 'Add Author'],
            ['route' => 'author_edit', 'display_name' => 'Edit Author'],
            ['route' => 'creator_add', 'display_name' => 'Add Creator'],
            ['route' => 'creator_edit', 'display_name' => 'Edit Creator'],
            ['route' => 'permissions_edit', 'display_name' => 'Edit User Permissions'],
            ['route' => 'cache_clear', 'display_name' => 'Clear Cache'],
            ['route' => 'modpack_code_add', 'display_name' => 'Add Modpack Code'],
            ['route' => 'modpack_code_edit', 'display_name' => 'Edit Modpack Code'],
            ['route' => 'mod_import', 'display_name' => 'Import Mod'],
            ['route' => 'modpack_tag', 'display_name' => 'Add Modpack Tag'],
            ['route' => 'youtube_add', 'display_name' => 'Add Youtube Video / Playlist'],
            ['route' => 'modpack_alias_add', 'display_name' => 'Add Modpack Alias'],
            ['route' => 'server_edit', 'display_name' => 'Edit Server'],
            ['route' => 'server_tag', 'display_name' => 'Manage Server Tags'],
        ]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('permissions');
    }

}
