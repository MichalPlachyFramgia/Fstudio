<?php
//API
Route::get('/installapp/{reg_id}/{device_id}/{package_name}/{app_id}', 'ApiController@addRegId');
Route::get('/install/', 'ApiController@addRegIdV2');
Route::get('/uninstall/{device_id}/{package_name}/{registration_id}', 'ApiController@uninstall');
Route::get('/updateVersion/{packageName}/{versionName}/{versionCode}', 'ApiController@updateVersion');
Route::get('/register', 'AuthController@register');

Route::group(['middleware' => ['web']], function () use ($router) {
    $router->post('/message/histories', ['uses' => 'GoogleCloudMessageController@showHistories', 'as' => 'gcm.histories']);
    $router->resource('gcm', 'GoogleCloudMessageController');
    $router->resource('app', 'ApplicationController');
    $router->get('/app/update_version/{app_id}', ['uses' => 'ApplicationController@editVersion', 'as' => 'app.update_version']);
    $router->post('/app/update_version/{app_id}', ['uses' => 'ApplicationController@updateVersion', 'as' => 'app.update_version']);
    $router->get('/gcm/message_histories/{message_id}', ['uses' => 'GoogleCloudMessageController@messageHistory', 'as' => 'gcm.message_histories']);
});
Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index');
});
Route::post('/search', 'GoogleCloudMessageController@search');
Route::post('/send_message', 'GoogleCloudMessageController@sendAll');
Route::get('users/charts/', 'UserController@getUsersApi');
Route::get('apps/charts/', 'UserController@getApplicationChart');


