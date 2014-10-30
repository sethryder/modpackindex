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

//search
Route::get('modpack/finder/{version}','SearchController@getModpackSearch');
Route::post('modpack/finder/{version}', 'SearchController@postModpackSearch');

//mods
Route::get('mods/{version?}', 'ModController@getModVersion');
Route::get('mod/add', 'ModController@getAdd');
Route::post('mod/add', 'ModController@postAdd');
Route::get('mod/{slug}', 'ModController@getMod');

Route::get('mod/edit/{id}', 'ModController@getEdit');
Route::post('mod/edit/{id}', 'ModController@postEdit');

//modpacks
Route::get('modpack/edit/{id}', 'ModpackController@getEdit');
Route::post('modpack/edit/{id}', 'ModpackController@postEdit');

Route::get('modpack/{version}/add', 'ModpackController@getAdd');
Route::post('modpack/{version}/add', 'ModpackController@postAdd');

Route::get('modpacks/{version?}', 'ModpackController@getModpackVersion');
Route::get('modpack/{version}/{slug}', 'ModpackController@getModpack');

//launchers
Route::get('launcher/{name}/{version?}', 'LauncherController@getLauncherVersion');

//authors
Route::get('author/add', 'AuthorController@getAdd');
Route::post('author/add', 'AuthorController@postAdd');

Route::get('author/edit/{id}', 'AuthorController@getEdit');
Route::post('author/edit/{id}', 'AuthorController@postEdit');

//creators
Route::get('creator/add', 'CreatorController@getAdd');
Route::post('creator/add', 'CreatorController@postAdd');

Route::get('creator/edit/{id}', 'CreatorController@getEdit');
Route::post('creator/edit/{id}', 'CreatorController@postEdit');


//user
Route::get('login', 'UserController@getLogin');
Route::post('login', 'UserController@postLogin');

Route::get('register', 'UserController@getRegister');
Route::post('register', 'UserController@postRegister');

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


//admin stuff

Route::get('user/permissions/{id}', 'getUserPermissions');

//about
Route::get('about', 'StaticPagesController@getAbout');

Route::get('checkauth', 'TestController@getCheckAuth');
Route::controller('test', 'TestController');
Route::controller('mods', 'ModController');

