<?php

namespace App\Foundations\Providers\FileReader;

use App\Foundations\Contracts\ReaderInterface;

class JsonFileReader implements ReaderInterface
{

    /**
     * Init
     */
    public function __construct(protected $path) {}

    /**
     * Set path file
     * 
     * @param string $path
     * @return ReaderInterface
     */
    public function setPath(string $path) :ReaderInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Unset path file
     * 
     * @return void
     */
    public function unsetPath() :void
    {
        $this->path = null;
    }

    /**
     * Parse file content
     * 
     * @return array
     */
    public function parse(): array
    {
        $items = json_decode(file_get_contents($this->path), true);
        return $items;
    }
}