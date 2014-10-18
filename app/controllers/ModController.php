<?php

class ModController extends BaseController
{
    public function getAll()
    {
        return Mod::all();
    }

    public function getAllModsTableJson()
    {

    }

    public function getVersionMods($version)
    {

    }

    public function getMod($id)
    {
        if ($mod = Mod::find($id))
        {
            print_r($mod);
            print_r($mod->versions);
        }
        else
        {
            echo 'Could not find mod with ID: '. $id;
        }
    }

    public function getTable($version)
    {
        $mods = Mod::all();
        $table_mods = array();

        foreach ($mods as $mod)
        {
            $table_mods[] = array(
                'name' => $mod->name,
                ''
            );
        }

        print_r($mods[0]);
    }
}