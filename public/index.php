<?php

#bootstrap
require_once __DIR__ . '/../bootstrap/app.php';

# Routes
$requestedRoute = explode('/', $_SERVER['REQUEST_URI'])[1];

$routers = match ($requestedRoute) {
    'api' => '/../routes/api.php',
    default => '/../routes/web.php',
};
    
require_once __DIR__ . $routers;

