<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::post('/login', 'App\Controller\UserController@login');
Router::post('/regist', 'App\Controller\UserController@regist');

Router::post('/note/{id:\d+}', 'App\Controller\NoteController@save');
Router::get('/note', 'App\Controller\NoteController@index');
