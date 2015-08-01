<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ServerUpdateQueueCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'server:updatequeue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues up servers that are due for an update..';

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
        //Check servers that need to be updated.
        $this->info('Starting server update check.');

        $update_time = Carbon\Carbon::now()->subSeconds(Config::get('app.server_update_interval'))->toDateTimeString();

        $to_update = Server::where('active', 1)->where('queued', 0)->where('last_check', '<=', $update_time)->get();

        foreach($to_update as $server) {
            $this->info('Queuing server ID ' . $server->id . ' for an update.');
            Queue::push('UpdateServer', ['server_id' => $server->id]);
            $server->queued = 1;
            $server->save();
        }

        $this->info('Finished server update check.');

        //Check for any servers that may be stalled for any reason.
        $this->info('Checking for stalled servers.');
        $last_update_time = Carbon\Carbon::now()->subMinutes(60)->toDateTimeString();

        $possible_stalled_servers = Server::where('active', 1)->where('queued', 1)->where('last_check', '<=', $last_update_time)->get();

        foreach ($possible_stalled_servers as $server) {
            $this->info('Resetting possible stalled server ID ' . $server->id . '.');
            $server->queued = 0;
            $server->save();
        }

        $this->info('Finished stalled server check.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
