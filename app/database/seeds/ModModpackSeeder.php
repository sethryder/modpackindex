<?php

class ModModpackSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modIds = Mod::lists('id');
        $modpackIds = Modpack::lists('id');

        foreach(range(1, 200) as $index)
        {
            DB::table('mod_modpack')->insert([
                'mod_id' => $faker->randomElement($modIds),
                'modpack_id' => $faker->randomElement($modpackIds),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}