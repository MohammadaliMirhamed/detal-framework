<?php

namespace App\Configs;

use App\Foundations\Contracts\StorageInterface;

class Storage implements StorageInterface
{
    /**
     * Save file to storage
     * 
     * @param string $path
     * @param array $file
     * @return string
     */
    public static function save(string $path, array $file): string
    {
        $filePath = $path . '/' . time() . '-' . basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $filePath);
        return $filePath;
    }

    /**
     * Delete file from storage
     * 
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool
    {
        if(file_exists($path)) {
            unlink($path);
            return true;
        }

        return false;
    }
}