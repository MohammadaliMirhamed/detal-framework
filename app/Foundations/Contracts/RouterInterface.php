<?php

namespace App\Foundations\Contracts;

interface RouterInterface
{
    /**
    * add a new route
    * @param string $expression
    * @param callable $function
    * @param string|array $method 
    * @return void
    */
    public static function add(string $expression, callable $function, string|array $method = 'GET'): void;

    /**
     * Get all routes
     * @return array
     */
    public static function getAll(): array;

    /**
     * Add funcation if path not found
     * @param callable $function
     */
    public static function pathNotFound(callable $function): void;

    /**
     * Add funcation if mathod not allowed
     * @param callable $function
     */
    public static function methodNotAllowed(callable $function): void;

    /**
     * Run routing
     * 
     * @param string $basepath
     * @param bool $case_matters
     * @param bool $trailing_slash_matters
     * @param bool $multimatch
     * @return mixed
     */    
    public static function run(string $basePath = '/', bool $caseMatters = false, bool $trailingSlashMatters = false, bool $multiMatch = false): mixed;
    
}