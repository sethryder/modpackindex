<?php

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mpi:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Initial setup of Modpack Index.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Begin install of Modpack Index...');

		$this->info('Check to see if database already exist...');

		if (Schema::hasTable('users'))
		{
			$this->error('Database already exists, please clear database if you want to re-install.');
            return '';
		}

        $this->info('Running migrations...');
        $this->call('migrate', []);

        $this->info('Create Admin user...');

        $admin_username = $this->ask('Admin username?');
        $admin_email = $this->ask('Admin email?');
        $admin_password = $this->secret('Admin password?');
        $admin_password_confirm = $this->secret('Confirm Admin password.');

        while ($admin_password != $admin_password_confirm) {
            $admin_password_confirm = $this->secret('Password confirmation does not match, try again.');
        }

        DB::table('users')->insert([
        [
            'username' => $admin_username,
            'password' => Hash::make($admin_password),
            'email' => $admin_email,
            'confirmation' => 0,
            'is_confirmed' => 1,
            'is_active' => 1,
            'is_admin' => 1,
            'is_moderator' => 0,
            'is_deleted' => 0,
            'register_ip' => '127.0.0.1',
            'last_ip' => '127.0.0.1'
        ]]);


        $this->info('Setting up permissions for admin user...');

        //setup permissions for the new admin user
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $i = 0;
        $number_of_permissions = 18;

        while ($i <= $number_of_permissions) {
            DB::table('permission_user')->insert([
                ['permission_id' => $i, 'user_id' => 1]
            ]);
            $i++;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Setup finished!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
        return [];
/*		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);*/
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        return [];
/*		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);*/
	}

}
