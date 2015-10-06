## Modpack Index

Modpack Index acts as a central database for the modpacks and mods available in the Minecraft community.

The site is still in active development and is growing/changing often. I hope to expand these docs soon with more information.

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
5. Run: `php artisan mpi:install` This will walk you through setting up your first admin user.
6. Have fun!


## Copyright

Copyright (c) 2015 Seth Ryder. See LICENSE for further details.