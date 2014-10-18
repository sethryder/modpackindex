<?php

class AuthorsModSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modIds = Mod::lists('id');
        $authorsId = Author::lists('id');

        foreach(range(1, 50) as $index)
        {
            DB::table('author_mod')->insert([
                'author_id' => $faker->randomElement($authorsId),
                'mod_id' => $faker->randomElement($modIds),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}