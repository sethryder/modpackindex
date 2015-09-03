<?php

class ModpackAliasController extends BaseController
{
    public function getAliases($version, $slug)
    {
        $modpack = Modpack::where('slug', '=', $slug)->first();
        $modpack_aliases = $modpack->aliases;

        $title = 'Add A Modpack Alias - ' . $this->site_name;

        return View::make('modpackaliases.view', [
            'chosen' => true,
            'title' => $title,
            'aliases' => $modpack_aliases,
            'modpack' => $modpack
        ]);
    }

    public function getAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add A Modpack Alias - ' . $this->site_name;

        return View::make('modpackaliases.add', ['chosen' => true, 'title' => $title]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) {
            return Redirect::route('index');
        }

        $title = 'Add A Modpack Alias - ' . $this->site_name;

        $input = Input::only('alias', 'modpack');

        $messages = [
            'unique' => 'A code for this launcher/modpack combination already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'alias' => 'required|unique:modpack_aliases,alias',
                'modpack' => 'required',
            ],
            $messages);

        if ($validator->fails()) {
            return Redirect::to('/modpack-alias/add')->withErrors($validator)->withInput();
        } else {
            $modpackalias = new ModpackAlias();

            $modpackalias->alias = $input['alias'];
            $modpackalias->modpack_id = $input['modpack'];

            $success = $modpackalias->save();

            if ($success) {
                return View::make('modpackaliases.add', ['title' => $title, 'success' => true]);
            } else {
                return Redirect::action('ModpackAliasController@getAdd')->withErrors(['message' => 'Unable to add modpack code.'])->withInput();
            }

        }
    }
}