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

//mods
Route::get('mods/{version?}', 'ModController@getModVersion');
Route::get('mod/add', 'ModController@getAdd');
Route::post('mod/add', 'ModController@postAdd');
Route::get('mod/{slug}', 'ModController@getMod');

//modpacks
Route::get('modpacks/{version?}', 'ModpackController@getModpackVersion');
Route::get('modpack/{version}/{slug}', 'ModpackController@getModpack');

//launchers
Route::get('launcher/{name}/{version?}', 'LauncherController@getLauncherVersion');

//authors
Route::get('author/add', 'AuthorController@getAdd');
Route::post('author/add', 'AuthorController@postAdd');

//api calls for json for the tables
Route::get('api/table/{type}_{version}.json', 'JSONController@getTableDataFile');
Route::get('api/table/{type}_{version}/{name}.json', 'JSONController@getTableDataFile');

Route::get('api/table/mods/{version}.json', 'JSONController@getTableMods');
Route::get('api/table/mod/modpacks/{name}.json', 'JSONController@getModModpacks');

Route::get('api/table/modpacks/{version}.json', 'JSONController@getTableModpacks');
Route::get('api/table/modpack/mods/{name}.json', 'JSONController@getTableModpackMods');


Route::get('api/table/launchers/{name}/{version}.json', 'JSONController@getTableLaunchers');

//user
Route::get('login', 'UserController@getLogin');


Route::controller('test', 'TestController');
Route::controller('mods', 'ModController');
