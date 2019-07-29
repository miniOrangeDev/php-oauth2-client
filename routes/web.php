<?php
Route::get('/', 'FrontendController@index' )->name('dashboard');
Route::get('login', 'FrontendController@getLogin' )->name('get-login');
Route::post('login', 'FrontendController@postLogin' )->name('post-login');
Route::get('register', 'FrontendController@getRegister' )->name('get-register');
Route::post('register', 'FrontendController@postRegister' )->name('post-register');
Route::get('logout','FrontendController@logoutUser')->name('logout');
Route::get('configure-app', 'ConfigureAppController@configureApp' )->name('configure-app');
Route::post('configure-app', 'ConfigureAppController@configureAppPost' )->name('post-configure-app');
Route::get('app-list', 'ConfigureAppController@appList' )->name('app-list');
Route::get('edit-app', 'ConfigureAppController@editApp' )->name('edit-app');
Route::post('edit-app', 'ConfigureAppController@updateApp' )->name('post-edit-app');
Route::post('attribute-mapping', 'ConfigureAppController@attributeMappingPost' )->name('attribute-mapping');
Route::get('delete-app', 'ConfigureAppController@deleteApp' )->name('delete-app');
Route::get('test-configuration', 'SsoController@testConfigure' )->name('test-configuration');
Route::get('sso','SsoController@index')->name('sso');
Route::get('callback', 'SsoController@getResponse' )->name('response');
Route::get('how-to-setup', 'ConfigureAppController@getSetup' )->name('how-to-setup');
Route::get('licensing', 'ConfigureAppController@getLicensing' )->name('licensing');