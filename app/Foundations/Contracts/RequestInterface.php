<?php

namespace App\Foundations\Contracts;

interface RequestInterface
{
    /**
     * Return all key/value of form request GET/POST
     * 
     * @return array
     */
    public function all(): array;

    /**
     * Return a value of form request GET/POST
     * 
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed;
    
    /**
     * Check does exist a field in form request
     * 
     * @param mixed $value
     * @return bool
     */
    public function has(string $name): bool;

    
    /**
     * Return a file of form request
     * 
     * @param string $name
     * @return mixed
     */
    public function file($name): mixed;
    
    /**
     * Set a new form request field
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     * Unset a field of form request
     * 
     * @param string $name
     * @return void
     */
    public function unset(string $name): void;
    
}