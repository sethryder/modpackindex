<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportMCFModlistCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'import:mcfmodlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import mods from MCF Modlist located at: http://modlist.mcf.li/';

    private $api_url = 'http://modlist.mcf.li/api/v3/';

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
        $this->info('Starting import from MCF Modlist...');

        $this->info('');
        $versions = MinecraftVersion::all();

        foreach ($versions as $version) {
            $this->info('Starting import of ' . $version->name . ' mods...');

            $mods = $this->getModJson($version->name);

            foreach ($mods as $mod) {
                $this->info('Processing ' . $mod->name);
                $database_mod = ImportMCFModlist::where('name', '=', $mod->name)->first();

                if (!$database_mod) {
                    $database_mod = new ImportMCFModlist;
                }

                $database_mod->name = $mod->name;
                $database_mod->description = $mod->desc;
                $database_mod->raw_authors = serialize($mod->author);
                if (isset($mod->source)) {
                    $database_mod->source = $mod->source;
                }
                $database_mod->url = $mod->link;
                $database_mod->raw_type = serialize($mod->type);
                $database_mod->raw_dependencies = serialize($mod->dependencies);
                $database_mod->raw_minecraft_versions = serialize($mod->versions);
                $database_mod->save();
            }
        }
    }

    private function getModJson($version)
    {
        $url = $this->api_url . $version . '.json';

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $raw_body = $response->getBody();
        $decoded_body = json_decode($raw_body);

        if (!$decoded_body) {
            return false;
        } else {
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
