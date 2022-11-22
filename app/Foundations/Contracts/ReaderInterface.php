<?php

namespace App\Foundations\Contracts;

interface ReaderInterface {

    /**
     * Set path file
     * 
     * @param string $path
     * @return ReaderInterface
     */
    public function setPath(string $path): ReaderInterface;

    /**
     * Unset path file
     * 
     * @param string $path
     * @return void
     */
    public function unsetPath(): void;

    /**
     * Parse file content
     * 
     * @return array
     */
    public function parse(): array;
}