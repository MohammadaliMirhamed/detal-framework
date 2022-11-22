<?php

namespace App\Foundations\Providers\CacheDriver;

use App\Foundations\Contracts\CacheInterface;

class RedisDriver implements CacheInterface
{

    /**
     * @var $client
     */
    protected $client;

    /**
     * init the Redis class
     */
    public function __construct()
    {
        $this->client = new \Redis();
        $this->client->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
    }

    /**
     * Get a kew/value
     * 
     * @param string $key
     * @return array
     */
    public function get($key): array
    {
        return json_decode($this->client->get($key), true);
    }
    
    /**
     * Set new key/value
     * 
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return void
     */
    public function set($key, $value, $ttl = null): void
    {
        $this->client->set($key, json_encode($value));
        if ($ttl) {
            $this->client->expire($key, $ttl);
        }
    }

    /**
     * Delete a key
     * 
     * @param string $key
     * @return void
     */
    public function delete($key): void
    {
        $this->client->del($key);
    }

    /**
     * Do you remember a key else run callback
     * 
     * @param string $key
     * @param int $ttl
     * @param callback $callback
     * @return callback
     */
    public function remember($key, $callback, $ttl = null): callable
    {
        $value = json_decode($this->client->get($key), true);

        if ($value) {
            return $value;
        }

        $value = $callback();
        $this->client->set($key, json_encode($value));

        if ($ttl) {
            $this->client->expire($key, $ttl);
        }

        return $value;
    }
}