<?php

class DatabaseSeeder extends Seeder {

    private $tables = [
        'mods',
        'minecraft_versions',
        'minecraft_version_mod',
        'authors',
        'author_mod',
        'launchers',
        'modpacks',
        'mod_modpack',
        'creators',
        'creator_modpack',
        'modpack_codes',
    ];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $this->cleanDatabase($this->tables);

        Eloquent::unguard();

        $this->call('ModsSeeder');
        $this->call('MinecraftVersionsSeeder');
        $this->call('MinecraftVersionModSeeder');
        $this->call('AuthorsSeeder');
        $this->call('AuthorsModSeeder');
        $this->call('LaunchersSeeder');
        $this->call('ModpackSeeder');
        $this->call('ModModpackSeeder');
        $this->call('CreatorsSeeder');
        $this->call('CreatorsModpackSeeder');
        $this->call('ModpackCodesSeeder');
		// $this->call('UserTableSeeder');
	}

    private function cleanDatabase($tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table)
        {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
