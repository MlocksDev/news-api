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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'api'
], function ($router) {

    // User Register Route => /api/register
    $router->post('register', 'AuthController@register');

    // User Login Route  => /api/login
    $router->post('login', 'AuthController@login');

    // User Logout Route  => /api/logout
    $router->post('logout', 'AuthController@logout');

    // Refresh Token Route => /api/refresh
    $router->post('refresh', 'AuthController@refresh');

    // User Profile Route  => /api/profile
    $router->get('profile', [
        'middleware' => 'auth',
        'uses' => 'AuthController@profile'
    ]);

    // Authors Routes
    $router->get('authors', "AuthorsController@list");
    $router->get('authors/by-user/{user_id}', "AuthorsController@listByUser");
    $router->get('authors/{id}', "AuthorsController@get");
    $router->post('authors', "AuthorsController@create");
    $router->put('authors/{id}', "AuthorsController@put");
    $router->delete('authors/{id}', "AuthorsController@delete");

    // News Routes
    $router->get('news', "NewsController@list");
    $router->get('news/by-author/{author_id}', "NewsController@listByAuthor");
    $router->get('news/{id}', "NewsController@get");
    $router->post('news', "NewsController@create");
    $router->put('news/{id}', "NewsController@put");
    $router->delete('news/{id}', "NewsController@delete");

    // Comment Routes
    $router->get('comment', "CommentController@list");
    $router->get('comment/by-news/{news_id}', "CommentController@listByNews");
    $router->get('comment/{id}', "CommentController@get");
    $router->post('comment', "CommentController@create");
    $router->put('comment/{id}', "CommentController@put");
    $router->delete('comment/{id}', "CommentController@delete");
});
