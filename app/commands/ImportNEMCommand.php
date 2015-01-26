<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportNEMCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:nem';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import mods from the Not Enough Mods located at: http://bot.notenoughmods.com/';

	private $api_url = 'http://bot.notenoughmods.com/';


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
		$this->info('Starting import from Not Enough Mods...');

		$this->info('');
		$versions = MinecraftVersion::all();

		foreach ($versions as $version)
		{
			$this->info('Starting import of ' . $version->name . ' mods...');

			$mods = $this->getModJson($version->name);

			foreach ($mods as $mod)
			{
				$versions_array = [];
				$authors_array = [];

				$this->info('Processing ' . $mod->name);
				$database_mod = ImportNEM::where('name', '=', $mod->name)->first();

				if (!$database_mod)
				{
					$database_mod = new ImportNEM;
					$versions_array[] = $version->name;
				}
				else
				{
					$versions_array = unserialize($database_mod->raw_minecraft_versions);

					if (!in_array($version->name, $versions_array))
					{
						$versions_array[] = $version->name;
					}
				}

				$exploded_authors = explode(',', $mod->author);

				foreach ($exploded_authors as $author)
				{
					$authors_array[] = ltrim($author);
				}

				$database_mod->name = $mod->name;
				$database_mod->raw_authors = serialize($authors_array);
				$database_mod->url = $mod->longurl;
				$database_mod->raw_dependencies = serialize($mod->dependencies);
				$database_mod->raw_minecraft_versions = serialize($versions_array);
				$database_mod->repo = $mod->repo;
				$database_mod->mod_version = $mod->version;
				$database_mod->nem_lastupdated = $mod->lastupdated;
				$database_mod->save();
			}
		}
	}

	private function getModJson($version)
	{
		$url = $this->api_url . $version . '.json';

		$client = new \GuzzleHttp\Client();
		$response = $client->get($url);

		if ($response->getStatusCode() != 200)
		{
			return false;
		}

		$raw_body = $response->getBody();
		$decoded_body = json_decode($raw_body);

		if (!$decoded_body)
		{
			return false;
		}
		else
		{
			return $decoded_body;
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
