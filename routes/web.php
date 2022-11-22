<?php 

use App\Configs\Router;

Router::add('/', function () {
    echo '.: Detal Framework v1.0.0 :.'; 
});

Router::pathNotFound(function() { http_response_code(404); });
Router::run('');