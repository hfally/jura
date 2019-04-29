<?php

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

// COURSE related routes
$router->get('courses', 'CourseController@index');
$router->get('course/{course_id}', 'CourseController@show');
$router->post('courses/create', 'CourseController@create');
$router->put('courses/update/{course_id}', 'CourseController@update');
$router->delete('courses/delete/{course_id}', 'CourseController@delete');

// TOPIC related routes
$router->get('topic/{topic_id}', 'TopicController@show');
$router->post('topics/create', 'TopicController@create');
$router->put('topics/update/{topic_id}', 'TopicController@update');
$router->delete('topics/delete/{topic_id}', 'TopicController@delete');

// BASIC LOCAL SETUP
$router->get('local-setup/generate-keys', 'SetupController@generate');