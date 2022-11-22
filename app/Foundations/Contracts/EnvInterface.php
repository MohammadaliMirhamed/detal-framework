<?php

namespace App\Foundations\Contracts;

interface EnvInterface
{
    /**
     * Load Env vars to system
     * @return void
     */
    public function load() :void;
}