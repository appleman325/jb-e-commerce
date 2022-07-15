<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Generate a random string for the application key in .env
$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});

// API end points
$router->group(['prefix' => 'api'], function () use ($router) {
    // Create a new user
    $router->post('/users', 'UserController@create');
    // Get user data based on user ID
    $router->get('/users/{id}', 'UserController@show');

    // Update/create an existing/new unseen notification based on user_id and notification type.
    // This will be useful when a user has multiple same type (e.g., product_approved) unseen notifications.
    // These same type unseen notifications can be combined as one notification in the notifications table.
    // Their notification data can be saved as json in the data column.
    $router->post('/users/{user_id}/notifications', 'NotificationController@create');
    // Get unseen notifications
    $router->get('/users/{user_id}/notifications', 'NotificationController@getNewNotifications');
    // Mark a notification as seen
    $router->post('/users/{user_id}/notifications/{notification_id}', 'NotificationController@readNotification');

    // Get all products
    // NOTE: Permission to access this API endpoint can be implemented.
    // For example, maybe only admins or certain users can see all products in admin panel.
    // The logic can be added through a customized middleware.
    $router->get('/products', 'ProductController@getProducts');
    // Get all active products
    $router->get('/active_products', 'ProductController@getActiveProducts');
    // Update a product (status, monthly inventory)
    $router->post('/products/{id}', 'ProductController@update');

    // Create an user application
    $router->post('/users/{user_id}/products', 'UserProductController@create');
    // Update an user application (status)
    $router->post('/users/{user_id}/products/{product_id}', 'UserProductController@update');
});
