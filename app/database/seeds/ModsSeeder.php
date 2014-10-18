<?php

class ModsSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        foreach(range(1, 50) as $index)
        {
            Mod::create([
                'name' => $faker->bs,
                'deck' => $faker->sentence(12),
                'website' => $faker->url,
                'download_link' => $faker->url,
                'donate_link' => $faker->url,
                'wiki_link' => $faker->url,
                'description' => $faker->paragraph(5),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}