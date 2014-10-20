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

Route::get('/', function()
{
	return View::make('hello');
});

//version lists
Route::get('mods/{version}/', 'ModController@getModVersion');

//api calls for json for the tables
Route::get('api/table/{type}_{version}.json', 'JSONController@getTableDataFile');
Route::get('api/table/mods/{version}.json', 'JSONController@getTableMods');
Route::get('api/table/modpacks/{version}.json', 'JSONController@getTableModpacks');


Route::controller('test', 'TestController');
Route::controller('mods', 'ModController');
