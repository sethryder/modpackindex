<?php

class ModpackCodesSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modpackIds = Modpack::lists('id');
        $laucherIds = Launcher::lists('id');

        foreach(range(1, 10) as $index)
        {
            ModpackCode::create([
                'code' => $faker->word,
                'modpack_id' => $faker->randomElement($modpackIds),
                'launcher_id' => $faker->randomElement($laucherIds),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}