<?php

class LaunchersSeeder extends Seeder {

    public function run()
    {
        Launcher::create([
            'name' => 'Feed the Beast',
            'deck' => 'Feed The Beast is a group of people that specialise in making high quality modpacks and maps for Minecraft.',
            'website' => 'http://www.feed-the-beast.com/',
            'download_link' => 'http://www.feed-the-beast.com/',
            'description' => 'Feed The Beast is a group of people that specialise in making high quality modpacks and maps for Minecraft. We started out as a skyblock style challenge map that focused on the use of tech style mods. These maps became extremely popular and in order to allow more people access to the maps the FTB Launcher was created.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        Launcher::create([
            'name' => 'ATLauncher',
            'deck' => 'ATLauncher is a simple and easy to use Minecraft Launcher which contains many different ModPacks including several public ModPacks for you to choose from.',
            'website' => 'http://www.atlauncher.com/',
            'download_link' => 'http://www.atlauncher.com/downloads',
            'description' => 'ATLauncher is a simple and easy to use Minecraft Launcher which contains 199 different ModPacks including 38 public ModPacks for you to choose from. We at ATLauncher do not make any of these packs which makes what we do very unique. We help others get ModPacks out there and in the hands of the public.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        Launcher::create([
            'name' => 'Technic Platoform',
            'deck' => 'A popular launcher bringing some of your favorite modpacks such as TPPI.',
            'website' => 'http://www.technicpack.net/',
            'download_link' => 'http://www.technicpack.net/download',
            'description' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        Launcher::create([
            'name' => 'Custom',
            'deck' => '',
            'website' => '',
            'download_link' => '',
            'description' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}