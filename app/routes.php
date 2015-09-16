<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Route::get('/', 'StaticPagesController@getNotLaunched');

Route::get('/test/route', ['as' => 'route_test', 'uses' => 'TestController@getRoute']);

/*
 * Administration Routes
 */

//Mods
Route::get('mod/add', ['as' => 'mod_add', 'uses' => 'ModController@getAdd']);
Route::post('mod/add', ['as' => 'mod_add', 'uses' => 'ModController@postAdd']);
Route::get('mod/edit/{id}', ['as' => 'mod_edit', 'uses' => 'ModController@getEdit']);
Route::post('mod/edit/{id}', ['as' => 'mod_edit', 'uses' => 'ModController@postEdit']);

//Modpacks
Route::get('modpack/{version}/add', ['as' => 'modpack_add', 'uses' => 'ModpackController@getAdd']);
Route::post('modpack/{version}/add', ['as' => 'modpack_add', 'uses' => 'ModpackController@postAdd']);
Route::get('modpack/edit/{id}', ['as' => 'modpack_edit', 'uses' => 'ModpackController@getEdit']);
Route::post('modpack/edit/{id}', ['as' => 'modpack_edit', 'uses' => 'ModpackController@postEdit']);

//Authors
Route::get('author/add', ['as' => 'author_add', 'uses' => 'AuthorController@getAdd']);
Route::post('author/add', ['as' => 'author_add', 'uses' => 'AuthorController@postAdd']);
Route::get('author/edit/{id}', ['as' => 'author_edit', 'uses' => 'AuthorController@getEdit']);
Route::post('author/edit/{id}', ['as' => 'author_edit', 'uses' => 'AuthorController@postEdit']);

//Creators
Route::get('creator/add', ['as' => 'creator_add', 'uses' => 'CreatorController@getAdd']);
Route::post('creator/add', ['as' => 'creator_add', 'uses' => 'CreatorController@postAdd']);
Route::get('creator/edit/{id}', ['as' => 'creator_edit', 'uses' => 'CreatorController@getEdit']);
Route::post('creator/edit/{id}', ['as' => 'creator_edit', 'uses' => 'CreatorController@postEdit']);

//Modpack Codes
Route::get('modpack-code/add', ['as' => 'modpack_code_add', 'uses' => 'ModpackCodeController@getAdd']);
Route::post('modpack-code/add', ['as' => 'modpack_code_add', 'uses' => 'ModpackCodeController@postAdd']);
Route::get('modpack-code/edit/{id}', ['as' => 'modpack_code_edit', 'uses' => 'ModpackCodeController@getEdit']);
Route::post('modpack-code/edit/{id}', ['as' => 'modpack_code_edit', 'uses' => 'ModpackCodeController@postEdit']);

//Modpack Aliases
Route::get('modpack-alias/add', ['as' => 'modpack_alias_add', 'uses' => 'ModpackAliasController@getAdd']);
Route::post('modpack-alias/add', ['as' => 'modpack_alias_add', 'uses' => 'ModpackAliasController@postAdd']);
Route::get('modpack/{version}/{slug}/aliases',
    ['as' => 'modpack_alias_add', 'uses' => 'ModpackAliasController@getAliases']);

//Modpack Tags
Route::get('tag/modpack/add', ['as' => 'modpack_tag', 'uses' => 'ModpackTagController@getAdd']);
Route::post('tag/modpack/add', ['as' => 'modpack_tag', 'uses' => 'ModpackTagController@postAdd']);
Route::get('tag/modpack/edit/{id}', ['as' => 'modpack_tag', 'uses' => 'ModpackTagController@getEdit']);
Route::post('tag/modpack/edit/{id}', ['as' => 'modpack_tag', 'uses' => 'ModpackTagController@postEdit']);

//Server Tags
Route::get('tags/server', ['as' => 'server_tag', 'uses' => 'ServerTagController@getTags']);
Route::get('tag/server/add', ['as' => 'server_tag', 'uses' => 'ServerTagController@getAdd']);
Route::post('tag/server/add', ['as' => 'server_tag', 'uses' => 'ServerTagController@postAdd']);
Route::get('tag/server/edit/{id}', ['as' => 'server_tag', 'uses' => 'ServerTagController@getEdit']);
Route::post('tag/server/edit/{id}', ['as' => 'server_tag', 'uses' => 'ServerTagController@postEdit']);

//Users
Route::get('user/permissions/{id}', ['as' => 'permissions_edit', 'uses' => 'UserController@getUserPermissions']);
Route::Post('user/permissions/{id}', ['as' => 'permissions_edit', 'uses' => 'UserController@postUserPermissions']);

//Youtube
Route::get('youtube/add', ['as' => 'youtube_add', 'uses' => 'YoutubeController@getadd']);
Route::post('youtube/add', ['as' => 'youtube_add', 'uses' => 'YoutubeController@postAdd']);

//Imports
Route::get('mod/import', ['as' => 'mod_import', 'uses' => 'ImportController@getStartImport']);
Route::post('mod/import', ['as' => 'mod_import', 'uses' => 'ImportController@postStartImport']);
Route::get('mod/import/{id}', ['as' => 'mod_import', 'uses' => 'ImportController@getImportMod']);
Route::post('mod/import/{id}', ['as' => 'mod_import', 'uses' => 'ImportController@postImportMod']);
Route::get('mod/import/{id}/author/{author_id?}', ['as' => 'mod_import', 'uses' => 'ImportController@getImportAuthor']);
Route::post('mod/import/{id}/author/{author_id}',
    ['as' => 'mod_import', 'uses' => 'ImportController@postImportAuthor']);

//Cache
Route::get('/cache/clear/{tag?}', ['as' => 'cache_clear', 'uses' => 'AdminController@getClearCache']);

//Misc
Route::get('memcache/stats', 'AdminController@getMemcacheStats');

/*
 * General Site Routes
 */

//search
Route::get('modpack/finder', 'SearchController@getModpackSearch');
Route::post('modpack/finder', 'SearchController@postModpackSearch');

//redirect old routes
Route::get('modpack/finder/{version}', function () {
    return Redirect::to('/modpack/finder', 301);
});

//compare
Route::get('modpacks/compare', 'ModpackController@getCompare');
Route::post('modpacks/compare', 'ModpackController@postCompare');

//Route::get('modpack/finder/{version}','SearchController@getModpackSearch');
//Route::post('modpack/finder/{version}', 'SearchController@postModpackSearch');

//mods
Route::get('mods/{version?}', 'ModController@getModVersion');
Route::get('mod/{slug}', 'ModController@getMod');
Route::get('mod/{slug}/spotlight/{id}-{creator}', ['as' => 'mod_spotlight', 'uses' => 'YoutubeController@getModVideo']);
Route::get('mod/{slug}/tutorial/{id}-{creator}', ['as' => 'mod_tutorial', 'uses' => 'YoutubeController@getModVideo']);

//modpacks
Route::get('modpacks/{version?}', 'ModpackController@getModpackVersion');
Route::get('modpack/{version}/{slug}', 'ModpackController@getModpack');
Route::get('modpack/{version}/{slug}/spotlight/{id}-{creator}', ['as' => 'modpack_spotlight', 'uses' => 'YoutubeController@getModpackVideo']);
Route::get('modpack/{version}/{slug}/lets-play/{id}-{creator}', ['as' => 'modpack_lets_play', 'uses' => 'YoutubeController@getModpackVideo']);

//launchers
Route::get('launcher/{name}/{version?}', 'LauncherController@getLauncherVersion');

//user
Route::get('profile/edit', 'UserController@getEditProfile');
Route::post('profile/edit', 'UserController@postEditProfile');
Route::get('profile/edit/password', 'UserController@getEditPassword');
Route::post('profile/edit/password', 'UserController@postEditPassword');

Route::get('profile/{username}/modpacks', 'UserController@getModpacks');
Route::get('profile/{username}/mods', 'UserController@getMods');
Route::get('profile/{username}/servers', 'UserController@getServers');
Route::get('profile/modpacks', 'UserController@getModpacks');
Route::get('profile/mods', 'UserController@getMods');
Route::get('profile/servers', 'UserController@getServers');

Route::get('profile/{username}', 'UserController@getProfile');
Route::get('profile', 'UserController@getProfile');

Route::get('login', 'UserController@getLogin');
Route::post('login', 'UserController@postLogin');
Route::get('logout', 'UserController@getLogout');

Route::get('register', 'UserController@getRegister');
Route::post('register', 'UserController@postRegister');

Route::get('forgot', 'UserController@getForgotPassword');
Route::post('forgot', 'UserController@postForgotPassword');

Route::get('reset/{token}', 'UserController@getResetPassword');
Route::post('reset/{token}', 'UserController@postResetPassword');

Route::get('user/verify/{confirmation}', 'UserController@getVerify');

//twitch
Route::get('streams', 'TwitchController@getStreams');
Route::get('stream/{channel}', 'TwitchController@getChannel');

//servers
Route::get('server/add', 'ServerController@getAdd');
Route::post('server/add', 'ServerController@postAdd');
Route::get('server/edit/{id}/{password?}', ['as' => 'server_edit', 'uses' => 'ServerController@getEdit']);
Route::post('server/edit/{id}/{password?}', ['as' => 'server_edit', 'uses' => 'ServerController@postEdit']);
Route::get('server/confirm/{id}/{password}', 'ServerController@getConfirm');
Route::get('server/query', 'ServerController@getQuery');
Route::get('servers/{slug?}', 'ServerController@getServers');
Route::post('servers/{slug?}', 'ServerController@postServers');
Route::get('server/{id}/{slug}', 'ServerController@getServer');

/*
 * API Routes
 */

//api for users
Route::get('api/v1/modpacks/{version}.json', 'APIController@getModpacks');
Route::get('api/v1/modpack/{id}.json', 'APIController@getModpack');
Route::get('api/v1/mods/{version}.json', 'APIController@getMods');
Route::get('api/v1/mod/{id}.json', 'APIController@getMod');
Route::get('api/v1/servers/{modpack}.json', 'APIController@getServers');
Route::get('api/v1/server/{id}.json', 'APIController@getServer');
Route::get('api/v1/streams/{modpack}.json', 'APIController@getStreams');
Route::get('api/v1/stream/{id}.json', 'APIController@getStream');

Route::get('api', function () {
    return Redirect::to('https://modpackindex-api-docs.readthedocs.org/en/latest/');
});

//api calls for json for the tables
Route::get('api/table/modpack_finder/{version}.json', 'JSONController@getModpackSearch');

Route::get('api/table/{type}_{version}.json', ['as' => 'tdf', 'uses' => 'JSONController@getTableDataFile']);
Route::get('api/table/{type}_{version}/{name}.json', ['as' => 'tdf_name', 'uses' => 'JSONController@getTableDataFile']);

Route::get('api/table/mods/{version}.json', 'JSONController@getTableMods');
Route::get('api/table/mod/modpacks/{name}.json', 'JSONController@getModModpacks');

Route::get('api/table/modpacks/compare.json', 'JSONController@getModpackCompare');
Route::get('api/table/modpacks/{version}.json', 'JSONController@getTableModpacks');
Route::get('api/table/modpack/mods/{name}.json', 'JSONController@getTableModpackMods');

Route::get('api/table/launchers/{name}/{version}.json', 'JSONController@getTableLaunchers');

Route::get('api/table/servers.json', 'JSONController@getServers');
Route::get('api/table/server/players/{id}.json', 'JSONController@getServerPlayers');
Route::get('api/table/server/mods/{id}.json', 'JSONController@getServerMods');

//mod select for pack finder
Route::get('api/select/mods/{version}.json', 'JSONController@getModsSelect');

//Route::get('api/jquery/mods/select.js', 'ModpackController@getModsJquery');

/*
 * Static Page Routes
 */

//static
Route::get('about', 'StaticPagesController@getAbout');
Route::get('about/modpack-codes', 'StaticPagesController@getPackCodes');
Route::get('contact', 'StaticPagesController@getContact');
Route::post('contact', 'StaticPagesController@postContact');
Route::get('submit-modpack', 'StaticPagesController@getSubmitModpack');
Route::post('submit-modpack', 'StaticPagesController@postSubmitModpack');
Route::get('submit-video', 'StaticPagesController@getSubmitVideo');
Route::post('submit-video', 'StaticPagesController@postSubmitVideo');

/*
 * Sitemap Routes
 */

//sitemap
Route::get('/sitemap/index.xml', 'SitemapController@getSitemapIndex');
Route::get('/sitemap/main.xml', 'SitemapController@getSitemapMain');
Route::get('/sitemap/launchers.xml', 'SitemapController@getSitemapLaunchers');
Route::get('/sitemap/modpacks.xml', 'SitemapController@getSitemapModpacks');
Route::get('/sitemap/mods.xml', 'SitemapController@getSitemapMods');
Route::get('/sitemap/servers.xml', 'SitemapController@getSitemapServers');
Route::get('/sitemap/videos.xml', 'SitemapController@getSitemapVideos');

//home
Route::get('/', ['as' => 'index', 'uses' => 'ModpackController@getModpackVersion']);

/*
 * Misc/Testing Routes
 */

//misc
Route::get('checkauth', 'TestController@getPackCodes');
Route::controller('test', 'TestController');
Route::controller('mods', 'ModController');

