<?php

namespace App\Foundations\Contracts;

interface ResponseInterface
{
    /**
     * Set body of response as text
     * 
     * @param mixed $data
     * @return ResponseInterface
     */
    public function body(mixed $data): ResponseInterface;
    
    /**
     * Set body of response as json
     * 
     * @param array $data
     * @return ResponseInterface
     */
    public function json(array $data): ResponseInterface;

    /**
     * Set http code
     * 
     * @return ResponseInterface
     */
    public function status(int $code = 200): ResponseInterface;
    
    /**
     * Set headers ruls
     * 
     * @param array $ruls
     * @return ResponseInterface
     */
    public function header(array $ruls): ResponseInterface;

    /**
     * Redirect route
     * 
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void;
    
}