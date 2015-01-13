<?php

class ModpackCodeController extends BaseController
{
    public function getAdd()
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Modpack Code - ' . $this->site_name;

        return View::make('modpackcodes.add', ['chosen' => true, 'title' => $title]);
    }

    public function postAdd()
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Add A Modpack Code - ' . $this->site_name;

        $input = Input::only('code', 'launcher', 'modpack');

        $messages = [
            'unique' => 'A code for this launcher/modpack combination already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'code' => 'required|unique:modpack_codes,code',
                'launcher' => 'required',
                'modpack' => 'required',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/modpack-code/add')->withErrors($validator)->withInput();
        }
        else
        {
            $modpackcode = new ModpackCode;

            $modpackcode->code = $input['code'];
            $modpackcode->modpack_id = $input['modpack'];
            $modpackcode->launcher_id = $input['launcher'];
            $modpackcode->last_ip = Request::getClientIp();

            $success = $modpackcode->save();

            if ($success)
            {
                Cache::tags('modpacks')->flush();
                return View::make('modpackcodes.add', ['title' => $title, 'success' => true]);
            }
            else
            {
                return Redirect::to('/modpack-codes/add')->withErrors(['message' => 'Unable to add modpack code.'])->withInput();
            }

        }
    }

    public function getEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Edit A Modpack Code - ' . $this->site_name;

        $modpackcode = ModpackCode::find($id);

        return View::make('modpackcodes.edit', ['title' => $title, 'modpackcode' => $modpackcode]);
    }

    public function postEdit($id)
    {
        if (!$this->checkRoute()) return Redirect::to('/');

        $title = 'Edit A Modpack Code - ' . $this->site_name;

        $input = Input::only('code', 'launcher', 'modpack');

        $modpackcode = ModpackCode::find($id);

        $messages = [
            'unique' => 'A code for this launcher/modpack combination already exists in the database!',
        ];

        $validator = Validator::make($input,
            [
                'code' => 'required|unique:modpack_codes,code,'.$modpackcode->id,
                'launcher' => 'required',
                'modpack' => 'required',
            ],
            $messages);

        if ($validator->fails())
        {
            return Redirect::to('/modpack-code/edit/' . $id)->withErrors($validator)->withInput();
        }
        else
        {
            $modpackcode->code = $input['code'];
            $modpackcode->modpack_id = $input['modpack'];
            $modpackcode->launcher_id = $input['launcher'];
            $modpackcode->last_ip = Request::getClientIp();

            $success = $modpackcode->save();

            if ($success)
            {
                Cache::tags('modpacks')->flush();
                return View::make('modpackcodes.edit', ['title' => $title, 'success' => true, 'modpackcode' => $modpackcode]);
            }
            else
            {
                return Redirect::to('/modpack-codes/edit/' . $id)->withErrors(['message' => 'Unable to add modpack code.'])->withInput();
            }

        }
    }
}