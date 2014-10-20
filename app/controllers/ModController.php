<?php

class ModController extends BaseController
{
    public function getAll()
    {
        return Mod::all();
    }

    public function getModVersion($version)
    {
        $table_javascript = '/api/table/mods_'. $version .'.json';
        $version = preg_replace('/-/', '.', $version);

        return View::make('mods.list', array('table_javascript' => $table_javascript, 'version' => $version));
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