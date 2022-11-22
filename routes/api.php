<?php

use App\Configs\Router;
use App\Controllers\Api\UserController;
use App\Controllers\Api\ImportController;


// User routes
Router::add('/users', [UserController::class, 'all'], 'GET');
Router::add('/users', [UserController::class, 'create'], 'POST');
Router::add('/users/([0-9]*)', [UserController::class, 'find'], 'GET');
Router::add('/users/([0-9]*)', [UserController::class, 'delete'], 'DELETE');

//Import routes
Router::add('/import/users', [ImportController::class, 'users'], 'POST');

   
// Run and mount routes
Router::pathNotFound(function() { http_response_code(404); });
Router::run('/api');