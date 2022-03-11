<?php
/*
 * The MIT License
 *
 * Copyright 2022 Martha Ribeiro Locks.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Models\Author\Author;
use App\Models\News\News;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//http:localhost:8080/api/v1
$router->group(['prefix' => 'api/v1', 'namespace' => 'V1\Author', 'as' => Author::class], function () use ($router) {

    $router->get('/authors', [
        'uses' => 'AuthorController@findAll'
    ]);

    $router->get('/authors/{id}', [
        'uses' => 'AuthorController@findOneBy'
    ]);

    $router->post('/authors', [
        'uses' => 'AuthorController@create'
    ]);

    $router->put('/authors/{param}', [
        'uses' => 'AuthorController@editBy'
    ]);

    $router->patch('/authors/{param}', [
        'uses' => 'AuthorController@editBy'
    ]);

    $router->delete('/authors/{id}', [
        'uses' => 'AuthorController@delete'
    ]);
});

$router->group(['prefix' => 'api/v1', 'namespace' => 'V1\News', 'as' => News::class], function () use ($router) {
    $router->get('/news', [
        'uses' => 'NewsController@findAll'
    ]);

    $router->get('/news/author/{author}', [
        'uses' => 'NewsController@findByAuthor'
    ]);

    $router->get('/news/{param}', [
        'uses' => 'NewsController@findBy'
    ]);

    $router->post('/news', [
        'uses' => 'NewsController@create'
    ]);

    $router->put('/news/{param}', [
        'uses' => 'NewsController@editBy'
    ]);

    $router->patch('/news/{param}', [
        'uses' => 'NewsController@editBy'
    ]);

    $router->delete('/news/{param}', [
        'uses' => 'NewsController@deleteBy'
    ]);

    $router->delete('/news/{author}', [
        'uses' => 'NewsController@deleteByAuthor'
    ]);
});

$router->group(['prefix' => 'api/v1', 'namespace' => 'V1\ImageNews'], function () use ($router) {
    $router->post('/imageNews', [
        'uses' => 'ImageNewsController@create'
    ]);
    $router->get('/imageNews', [
        'uses' => 'ImageNewsController@findAll'
    ]);
    $router->get('/imageNews/news/{news}', [
        'uses' => 'ImageNewsController@findByNews'
    ]);
    $router->get('/imagens-noticias/{id}', [
        'uses' => 'ImageNewsController@findOneBy'
    ]);
    $router->get('/imageNews/{param}', [
        'uses' => 'ImageNewsController@findBy'
    ]);
    $router->put('/imageNews/{param}', [
        'uses' => 'ImageNewsController@editBy'
    ]);
    $router->patch('/imageNews/{param}', [
        'uses' => 'ImageNewsController@editBy'
    ]);
    $router->delete('/imageNews/news/{news}', [
        'uses' => 'ImageNewsController@deleteByNews'
    ]);
    $router->delete('/imageNews/{id}', [
        'uses' => 'ImageNewsController@delete'
    ]);
});
