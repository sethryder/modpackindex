<?php

class CreatorsSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        foreach(range(1, 12) as $index)
        {
            $username = $faker->userName;
            Creator::create([
                'name' => $username,
                'deck' => $faker->sentence(12),
                'website' => $faker->url,
                'donate_link' => $faker->url,
                'bio' => $faker->paragraph(5),
                'slug' => Str::slug($username),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}