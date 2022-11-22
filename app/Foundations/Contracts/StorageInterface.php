<?php

namespace App\Foundations\Contracts;

interface StorageInterface {

    /**
     * Save file to storage
     * 
     * @param string $path
     * @param array $file
     * @return string
     */
    public static function save(string $path, array $file): string;

    /**
     * Delete file from storage
     * 
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool;
}