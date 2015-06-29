<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TwitchUpdateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'twitch:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update online Twitch streams and match them to Modpacks.';

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
		$this->info('Starting Twitch Streams update...');
		$this->updateTwitchStreams();
		$this->info('Matching Twitch Streams to Modpacks...');
		$this->matchTwitchStreamsToModpacks();
		$this->info('Finished.');

	}

	private function matchTwitchStreamsToModpacks()
	{
		$modpacks_array = [];
		$streams_array = [];

		$modpacks = Modpack::all();
		$modpack_aliases = ModpackAlias::all();
		$streams = TwitchStream::where('online', 1)->get();

		foreach ($modpacks as $modpack)
		{
			$modpacks_array[] = [
				'id' => $modpack->id,
				'name' => $modpack->name
			];
		}

		foreach ($modpack_aliases as $alias)
		{
			$modpacks_array[] = [
				'id' => $alias->modpack_id,
				'name' => $alias->alias
			];
		}

		foreach ($streams as $stream)
		{
			$id = $stream->channel_id;
			$streams_array[$id] = $stream->status;
		}

		foreach ($streams_array as $s_id => $stream)
		{
			$stream_database = TwitchStream::find($s_id);

			foreach ($modpacks_array as $mod)
			{
				preg_match('/'. $mod['name'] .'/i', $stream, $match);
				if ($match)
				{
					$stream_database->modpack_id = $mod['id'];
					$stream_database->save();

					$this->info('Matched \'' . $stream . '\' to '. $mod['name']);

					break;
				}
				else
				{
					$stream_database->delete();
				}
			}
		}
	}

	private function updateTwitchStreams()
	{
		$known_online_channel_ids = [];
		$known_channel_ids = [];
		$online_channel_ids = [];

		$twitch = new TwitchStream;
		$known_streams = $twitch::all();
		$online_streams = $twitch->getOnlineStreams();
		$language_codes = TwitchStream::getLanguageCodes();

		foreach ($online_streams as $online_stream)
		{
			$online_channel_ids[] = $online_stream->channel->_id;
		}

		foreach ($known_streams as $known_stream)
		{
			if ($known_stream->online == 1)
			{
				$known_online_channel_ids[] = $known_stream->channel_id;
			}
			$known_channel_ids[] = $known_stream->channel_id;
		}

		foreach ($known_online_channel_ids as $channel_id)
		{
			$database_channel = new TwitchStream;
			$stream = $database_channel::where('channel_id', $channel_id)->first();

			if (!in_array($channel_id, $online_channel_ids))
			{
				$stream->delete();
			}
			else
			{
				$channel = $online_streams[$channel_id];

				if (isset($channel->channel->status)) $stream->status = $channel->channel->status;
				if (isset($channel->viewers)) $stream->viewers = $channel->viewers;
				if (isset($channel->channel->followers)) $stream->followers = $channel->channel->followers;
				if (isset($channel->preview->medium)) $stream->preview = str_replace('http://', 'https://', $channel->preview->medium);
				if (isset($channel->channel->url)) $stream->url = $channel->channel->url;
				if (isset($channel->channel->display_name)) $stream->display_name = $channel->channel->display_name;

				if (isset($channel->channel->broadcaster_language))
				{
					if (array_key_exists($channel->channel->broadcaster_language, $language_codes))
					{
						$language_code = $channel->channel->broadcaster_language;
						$language = $language_codes[$language_code];
						$stream->language = $language;
					}
					else
					{
						$stream->language = $channel->channel->broadcaster_language;
					}
				}
				$stream->online = 1;
				$stream->save();
			}
		}

		foreach ($online_streams as $online_stream)
		{
			if (!in_array($online_stream->channel->_id, $known_channel_ids))
			{
				$database_channel = new TwitchStream;

				if (isset($online_stream->channel->_id)) $database_channel->channel_id = $online_stream->channel->_id;
				if (isset($online_stream->channel->display_name)) $database_channel->display_name = $online_stream->channel->display_name;
				if (isset($online_stream->channel->url)) $database_channel->url = $online_stream->channel->url;
				if (isset($online_stream->channel->status)) $database_channel->status = $online_stream->channel->status;
				if (isset($online_stream->viewers)) $database_channel->viewers = $online_stream->viewers;
				if (isset($online_stream->channel->followers)) $database_channel->followers = $online_stream->channel->followers;
				if (isset($online_stream->preview->medium)) $database_channel->preview = str_replace('http://', 'https://', $online_stream->preview->medium);

				if (isset($online_stream->channel->broadcaster_language))
				{
					if (array_key_exists($online_stream->channel->broadcaster_language, $language_codes))
					{
						$language_code = $online_stream->channel->broadcaster_language;
						$language = $language_codes[$language_code];
						$database_channel->language = $language;
					}
					else
					{
						$database_channel->language = $online_stream->channel->broadcaster_language;
					}
				}
				$database_channel->online = 1;

				$database_channel->save();
			}
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
