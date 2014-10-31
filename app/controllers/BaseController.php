<?php

class BaseController extends Controller {

    public $site_name;

    public function __construct()
    {
        $this->site_name = Config::get('app.site_name');
    }

    public function checkRoute()
    {
        $route = Route::currentRouteName();
        $user_id = Auth::id();

        $permission_object = Permission::where('route', '=', $route)->first();
        $permission_id = $permission_object->id;

        $count = $users = DB::table('permission_user')->where('permission_id', '=', $permission_id)
            ->where('user_id', '=', $user_id)->count();

        if ($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
