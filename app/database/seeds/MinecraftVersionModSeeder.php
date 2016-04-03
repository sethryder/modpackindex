<?php

class MinecraftVersionModSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modIds = Mod::lists('id');
        $minecraftVersionIds = MinecraftVersion::lists('id');

        foreach ($modIds as $modId)
        {
            shuffle($minecraftVersionIds);

            DB::table('minecraft_version_mod')->insert([
                'minecraft_version_id' => $minecraftVersionIds[0],
                'mod_id' => $modId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if (mt_rand(0, 1)) {
                DB::table('minecraft_version_mod')->insert([
                    'minecraft_version_id' => $minecraftVersionIds[1],
                    'mod_id' => $modId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            if (mt_rand(0, 1)) {
                DB::table('minecraft_version_mod')->insert([
                    'minecraft_version_id' => $minecraftVersionIds[0],
                    'mod_id' => $modId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}