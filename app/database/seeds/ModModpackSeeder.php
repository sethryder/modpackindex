<?php

class ModModpackSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modpacks = Modpack::all();

        foreach ($modpacks as $modpack) {
            $raw_version = MinecraftVersion::where('id', '=', $modpack->minecraft_version_id)->with('mods')->first();
            $raw_mods = $raw_version->mods()->lists('mod_id');

            $mod_count = mt_rand(20, 50);

            foreach (range(1, $mod_count) as $index) {
                DB::table('mod_modpack')->insert([
                    'mod_id' => $faker->randomElement($raw_mods),
                    'modpack_id' => $modpack->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}