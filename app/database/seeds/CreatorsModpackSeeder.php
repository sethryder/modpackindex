<?php

class CreatorsModpackSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modpackIds = Modpack::lists('id');
        $creatorssId = Creator::lists('id');

        foreach(range(1, 15) as $index)
        {
            DB::table('creator_modpack')->insert([
                'creator_id' => $faker->randomElement($creatorssId),
                'modpack_id' => $faker->randomElement($modpackIds),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}