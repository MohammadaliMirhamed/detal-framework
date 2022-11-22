<?php

#psr-4 auto load
require __DIR__.'/../vendor/autoload.php';

use App\Configs\Env;

#load envs
(new Env(__DIR__ . '/../.env'))->load();