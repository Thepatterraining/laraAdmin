<?php

Route::post('login', 'Auth\LoginController@index');

Route::post('user', 'Auth\RegisterController@index');
