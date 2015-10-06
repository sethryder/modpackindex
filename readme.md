## Modpack Index

Modpack Index acts as a central database for the modpacks and mods available in the Minecraft community. It offers a
variety of features including modpack finder, modpack comparison, servers, streams, and more.

The site is still in active development and somethings could change.

## Live Site

The site is currently running at [www.modpackindex.com](http://www.modpackindex.com)

## Requirements

* PHP >= 5.4
* Composer
* MCrypt PHP Extension
* MySQL / Postgres / SQLite

## Installing

1. Clone the repo: `git clone https://github.com/sethryder/modpackindex.git`.
2. Install Dependencies: `composer install`.
3. Set an encryption key in `app/config/app.php`, more info about it in the config file.
4. Enter you database credentials in `app/config/database.php`.
5. Run: `php artisan mpi:install`, this will run database migrations and walk you through setting up your first admin user.
6. Have fun!

## Additional Configuration

Here is some additional configuration that you will need to do to use every feature.

##### Youtube

Add your Youtube API key to `app/config/services.php` to allow MPI to properly import video information.

##### Bit.ly

Add your Bit.ly token to `app/config/services.php` to allow MPI to convert Bit.ly URLs to the full URL on import.

## Commands / Crons

Modpack Index depends on commands to keep some information up to date.

`php artisan mpi:install`

* Use this to setup the first default admin user.
* You only need to run this once when you setup the site.

`php artisan import:mcfmodlist`

* Import / Update mods from the [MCF Mod List](http://modlist.mcf.li/) and add them to the import system.
* Recommended to setup as a cron that runs once a day.

`php artisan import:nem`

* Import / Update mods from the [Not Enough Mods](https://bot.notenoughmods.com/) and add them to the import system.
* Recommended to setup as a cron that runs once a day.

`php artisan server:updatequeue`

* Check to see which servers are due for an update and add them to the queue.
* Recommended to setup as a cron that runs every minute.

`php artisan twitch:update`

* Update stream from Twich and match them to modpacks in the database.
* Recommended to setup as a cron that every 5 minutes or so.

## IRC

Have questions? Want to chat? Visit our IRC Channel!

[#ModpackIndex](https://webchat.esper.net/?channels=ModpackIndex) on [EsperNet](https://www.esper.net/).

## Copyright

Copyright (c) 2015 Seth Ryder. See LICENSE for further details.