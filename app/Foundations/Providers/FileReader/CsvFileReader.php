<?php

namespace App\Foundations\Providers\FileReader;

use App\Foundations\Contracts\ReaderInterface;

class CsvFileReader implements ReaderInterface
{

    /**
     * Init 
     */
    public function __construct(protected $path)
    {}

    /**
     * Set path file
     * 
     * @param string $path
     * @return ReaderInterface
     */
    public function setPath(string $path): ReaderInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Unset path file
     * 
     * @return void
     */
    public function unsetPath(): void
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
        $items = array_map("str_getcsv", file($this->path, FILE_SKIP_EMPTY_LINES));    
        $header = array_shift($items);

        foreach ($items as $key => $value) {    
            $items[$key] = array_combine($header, $value);
        }

        return $items;
    }
}