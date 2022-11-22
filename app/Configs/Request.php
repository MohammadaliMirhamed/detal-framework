<?php

namespace App\Configs;

use App\Foundations\Contracts\RequestInterface;

class Request implements RequestInterface
{

    /**
     * Request resources
     */
    protected $resources = [];

    /**
     * Init mount requests
     */
    public function __construct()
    {
        $this->resources['get'] = $_REQUEST;
        $this->resources['file'] = $_FILES;
    }
    
    /**
     * Return all key/value of form request GET/POST
     * 
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->resources['get'], $this->resources['file']);
    }

    /**
     * Return a value of form request GET/POST
     * 
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {
        return $this->resources['get'][$name] ?? '';
    }
    
    /**
     * Check does exist a field in form request
     * 
     * @param mixed $value
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->resources['get'][$name] ?? false;
    }

    
    /**
     * Return a file of form request
     * 
     * @param string $name
     * @return mixed
     */
    public function file($name): mixed
    {
        return $this->resources['file'][$name] ?? '';
    }
    
    /**
     * Set a new form request field
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->resources['get'] = array_merge($this->resources['get'], [$key => $value]);
    }

    /**
     * Unset a field of form request
     * 
     * @param string $name
     * @return void
     */
    public function unset(string $name): void
    {
        foreach (['get', 'file'] as $type) {
            unset($this->resources[$type][$name]);
        }
    }
}