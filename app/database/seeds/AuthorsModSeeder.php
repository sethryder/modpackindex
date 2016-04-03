<?php

class AuthorsModSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();

        $modIds = Mod::lists('id');
        $authorsId = Author::lists('id');

        foreach ($modIds as $modId) {
            DB::table('author_mod')->insert([
                'author_id' => $faker->randomElement($authorsId),
                'mod_id' =>$modId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}