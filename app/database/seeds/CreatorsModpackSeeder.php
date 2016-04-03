<?php

class CreatorsModpackSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modpackIds = Modpack::lists('id');
        $creatorssId = Creator::lists('id');

        foreach ($modpackIds as $modpackId) {
            DB::table('creator_modpack')->insert([
                'creator_id' => $faker->randomElement($creatorssId),
                'modpack_id' => $modpackId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}