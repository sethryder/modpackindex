<?php

class MinecraftVersionModSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modIds = Mod::lists('id');
        $minecraftVersionIds = MinecraftVersion::lists('id');

        foreach(range(1, 50) as $index)
        {
           DB::table('minecraft_version_mod')->insert([
               'minecraft_version_id' => $faker->randomElement($minecraftVersionIds),
               'mod_id' => $faker->randomElement($modIds),
               'created_at' => date('Y-m-d H:i:s'),
               'updated_at' => date('Y-m-d H:i:s')
           ]);
        }
    }
}