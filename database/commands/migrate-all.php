<?php

// bootstrap
require_once __DIR__ . '/../../bootstrap/app.php';

use App\Configs\Database;

$path = __DIR__ . '/../../database/tables/';

// import all sql files
$files =  glob( $path . '*.sql');
foreach($files as $file) {
    (new Database())->raw(file_get_contents($file));
    print($file . ' has imported to database ' . PHP_EOL);
}