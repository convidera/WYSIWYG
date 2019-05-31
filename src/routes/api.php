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
    Route::match(['put', 'patch'], '/WYSIWYG/{textElement?}', 'WYSIWYGController@update');
    Route::post('/WYSIWYG/markdown-parser', 'MarkdownParserController@parse');
});
