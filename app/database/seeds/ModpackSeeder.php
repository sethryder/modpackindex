<?php

class ModpackSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $launcherIds = Launcher::lists('id');

        foreach(range(1, 10) as $index)
        {
            Modpack::create([
                'name' => $faker->bs . ' Modpack',
                'launcher_id' => $faker->randomElement($launcherIds),
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