<?php

// bootstrap
require_once __DIR__ . '/../../bootstrap/app.php';

use App\Configs\Database;

$path = __DIR__ . '/../../database/tables/' . $argv[1];

// import a specefic sql file
if (file_exists($file)) {
    (new Database())->raw(file_get_contents($file));
    print($file . ' has imported to database ' . PHP_EOL);
}