<?php

class BaseController extends Controller {

    public $site_name;

    public function __construct()
    {
        $this->site_name = Config::get('app.site_name');

        if (Auth::check())
        {
            $user_permissions = $this->UserPermissions();

            if ($user_permissions)
            {
                View::share('user_permissions', $this->UserPermissions());
            }
        }
    }

    public function checkRoute()
    {
        if (App::environment() == 'dev')
        {
            return true;
        }

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

    public function UserPermissions()
    {
        if (Auth::check())
        {
            $user_id = Auth::id();
            $cache_key = "user-". $user_id . "-permissions";

            if (Cache::tags('user-permissions')->has($cache_key))
            {
                $permission_array = Cache::tags('user-permissions')->get($cache_key);
            }
            else
            {
                if (Auth::user()->is_admin)
                {
                    $raw_permission_array = [];
                    $permission_array = [];
                    $permission_objects = Permission::all();
                    $user_permissions = DB::table('permission_user')->where('user_id', '=', $user_id)->get();

                    foreach ($user_permissions as $user_permission)
                    {
                        $permission_id = $user_permission->permission_id;
                        $raw_permission_array[$permission_id] = 1;
                    }

                    foreach ($permission_objects as $permission)
                    {
                        $route_name = $permission->route;
                        $permission_id = $permission->id;

                        if (isset($raw_permission_array[$permission_id]))
                        {
                            $permission_array[$route_name] = $raw_permission_array[$permission_id];
                        }
                        else
                        {
                            $permission_array[$route_name] = 0;
                        }
                    }
                }
                else
                {
                    $permission_array = false;
                }
                Cache::tags('user-permissions')->put($cache_key, $permission_array, 60);
            }
        }
        return $permission_array;
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
