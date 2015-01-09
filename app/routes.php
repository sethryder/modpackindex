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

Route::get('/', 'ModpackController@getModpackVersion');
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

//Users
Route::get('user/permissions/{id}', ['as' => 'permissions_edit', 'uses' => 'UserController@getUserPermissions']);
Route::Post('user/permissions/{id}', ['as' => 'permissions_edit', 'uses' => 'UserController@postUserPermissions']);

//Cache
Route::get('/cache/clear/{tag?}', ['as' => 'cache_clear', 'uses' => 'AdminController@getClearCache']);



/*
 * General Site Routes
 */

//search
Route::get('modpack/finder/{version}','SearchController@getModpackSearch');
Route::post('modpack/finder/{version}', 'SearchController@postModpackSearch');

//mods
Route::get('mods/{version?}', 'ModController@getModVersion');
Route::get('mod/{slug}', 'ModController@getMod');


//modpacks
Route::get('modpacks/{version?}', 'ModpackController@getModpackVersion');
Route::get('modpack/{version}/{slug}', 'ModpackController@getModpack');

//launchers
Route::get('launcher/{name}/{version?}', 'LauncherController@getLauncherVersion');


//user
Route::get('login', 'UserController@getLogin');
Route::post('login', 'UserController@postLogin');

//Route::get('register', 'UserController@getRegister');
//Route::post('register', 'UserController@postRegister');

Route::get('user/verify/{confirmation}', 'UserController@getVerify');


//api calls for json for the tables
Route::get('api/table/{type}_{version}.json', 'JSONController@getTableDataFile');
Route::get('api/table/{type}_{version}/{name}.json', 'JSONController@getTableDataFile');

Route::get('api/table/mods/{version}.json', 'JSONController@getTableMods');
Route::get('api/table/mod/modpacks/{name}.json', 'JSONController@getModModpacks');

Route::get('api/table/modpacks/{version}.json', 'JSONController@getTableModpacks');
Route::get('api/table/modpack/mods/{name}.json', 'JSONController@getTableModpackMods');

Route::get('api/table/launchers/{name}/{version}.json', 'JSONController@getTableLaunchers');

Route::get('api/table/modpackfinder/{version}.json', 'JSONController@getModpackSearch');

//Route::get('api/jquery/mods/select.js', 'ModpackController@getModsJquery');

//about
Route::get('about', 'StaticPagesController@getAbout');
Route::get('contact', 'StaticPagesController@getContact');
Route::post('contact', 'StaticPagesController@postContact');

Route::get('checkauth', 'TestController@getCheckAuth');
Route::controller('test', 'TestController');
Route::controller('mods', 'ModController');

