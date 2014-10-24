<?php

class BaseController extends Controller {

    public $site_name;

    public function __construct()
    {
        $this->site_name = Config::get('app.site_name');
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
