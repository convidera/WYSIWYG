<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'     => 'api',
    'namespace'  => 'Convidera\WYSIWYG\Http\Controllers',
    'middleware' => [ 'web', 'auth' ],
], function() {
    Route::match(['put', 'patch'], '/WYSIWYG/text/{textElement}', 'WYSIWYGController@updateText');
    Route::match(['put', 'patch'], '/WYSIWYG/text/', 'WYSIWYGController@updateTexts');

    Route::match(['put', 'patch'], '/WYSIWYG/media/{mediaElement}', 'WYSIWYGController@updateMedia');
    Route::match(['put', 'patch'], '/WYSIWYG/media/', 'WYSIWYGController@updateMedias');
});
