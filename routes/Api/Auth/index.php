<?php

Route::post('login', 'Auth\LoginController@index');

Route::post('user', 'Auth\RegisterController@index');

Route::get('users', 'Auth\UserController@getList');

Route::put('user/{id}', 'Auth\UserController@updateInfo');

Route::delete('user/{id}', 'Auth\UserController@deleteInfo');




Route::post('role', 'Auth\RoleController@createInfo');

Route::get('roles', 'Auth\RoleController@getList');

Route::put('role/{id}', 'Auth\RoleController@updateInfo');

Route::delete('role/{id}', 'Auth\RoleController@deleteInfo');


Route::post('permission', 'Auth\PermissionController@createInfo');

Route::get('permissions', 'Auth\PermissionController@getList');

Route::put('permission/{id}', 'Auth\PermissionController@updateInfo');

Route::delete('permission/{id}', 'Auth\PermissionController@deleteInfo');

Route::get('permission/tree', 'Auth\PermissionController@getTree');

