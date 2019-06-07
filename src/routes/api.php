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
    Route::get('/WYSIWYG/config', 'ConfigController@showConfig');

    Route::match(['put', 'patch'], '/WYSIWYG/text/{textElement}', 'WYSIWYGController@updateText');
    Route::match(['put', 'patch'], '/WYSIWYG/text/', 'WYSIWYGController@updateTexts');

    Route::match(['put', 'patch', 'post'], '/WYSIWYG/media/{mediaElement}', 'WYSIWYGController@updateMedia');
    Route::match(['put', 'patch', 'post'], '/WYSIWYG/media/', 'WYSIWYGController@updateMedias');

    Route::post('/WYSIWYG/markdown-parser', 'MarkdownParserController@parse');
});
