<?php 

namespace App\Foundations\Contracts;

interface CacheInterface
{
    /**
     * Get a kew/value
     * 
     * @param string $key
     * @return array
     */
    public function get($key): array;
    
    /**
     * Set new key/value
     * 
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return void
     */
    public function set($key, $value, $ttl = null): void;
    
    /**
     * Delete a key
     * 
     * @param string $key
     * @return void
     */
    public function delete($key): void;

    /**
     * Do you remember a key else run callback
     * 
     * @param string $key
     * @param int $ttl
     * @param callback $callback
     * @return callback
     */
    public function remember($key, $callback, $ttl = null): callable;
    
}
