<?php

class ModpackSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $launcherIds = Launcher::lists('id');
        $minecraftVersionIds = MinecraftVersion::lists('id');

        foreach(range(1, 30) as $index)
        {
            $name = $faker->bs;
            Modpack::create([
                'name' => $name,
                'launcher_id' => $faker->randomElement($launcherIds),
                'minecraft_version_id' => $faker->randomElement($minecraftVersionIds),
                'deck' => $faker->sentence(12),
                'website' => $faker->url,
                'download_link' => $faker->url,
                'donate_link' => $faker->url,
                'wiki_link' => $faker->url,
                'description' => $faker->paragraph(5),
                'slug' => Str::slug($name),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}