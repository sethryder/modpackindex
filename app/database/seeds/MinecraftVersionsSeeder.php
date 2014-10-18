<?php

class MinecraftVersionsSeeder extends Seeder {

    public function run()
    {
        MinecraftVersion::create([
            'name' => '1.6.4',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        MinecraftVersion::create([
            'name' => '1.7.10',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}