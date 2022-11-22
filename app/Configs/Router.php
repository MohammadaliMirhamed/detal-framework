<?php

namespace App\Configs;

use App\Foundations\Contracts\RouterInterface;

class Router implements RouterInterface
{

    /**
     * @var array $routes
     */
    private static $routes = Array();

    /**
     * @var mixed $pathNotFound
     */
    private static $pathNotFound = null;

    /**
     * @var mixed $methodNotAllowed
     */
    private static $methodNotAllowed = null;

    /**
    * add a new route
    * @param string $expression    Route string or expression
    * @param callable $function    Function to call if route with allowed method is found
    * @param string|array $method  Either a string of allowed method or an array with string values
    * @return void
    */
    public static function add($expression, $function, $method = 'get'): void
    {
        array_push(self::$routes, Array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method
        ));
    }

    /**
     * Get all routes
     * @return array
     */
    public static function getAll(): array
    {
        return self::$routes;
    }

    /**
     * Add funcation if path not found
     * @param callable $function
     */
    public static function pathNotFound($function): void
    {
        self::$pathNotFound = $function;
    }

    /**
     * Add funcation if mathod not allowed
     * @param callable $function
     */
    public static function methodNotAllowed($function): void
    {
        self::$methodNotAllowed = $function;
    }

    /**
     * Run routing
     * 
     * @param string $basePath
     * @param bool $caseMatters
     * @param bool $trailingSlashMatters
     * @param bool $multiMatch
     * @return mixed
     */
    public static function run($basePath = '/', $caseMatters = false, $trailingSlashMatters = false, $multiMatch = false): mixed
    {
        // The basePath never needs a trailing slash
        // Because the trailing slash will be added using the route expressions
        $basePath = rtrim($basePath, '/');

        // Parse current URL
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        $path = '/';

        // If there is a path available
        if (isset($parsed_url['path'])) {
            // If the trailing slash matters
            if ($trailingSlashMatters) {
                $path = $parsed_url['path'];
            } else {
                // If the path is not equal to the base path (including a trailing slash)
                if ($basePath.'/'!=$parsed_url['path']) {
                    // Cut the trailing slash away because it does not matters
                    $path = rtrim($parsed_url['path'], '/');
                } else {
                    $path = $parsed_url['path'];
                }
            }
        }

        $path = urldecode($path);

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $pathMatchFound = false;

        $routeMatchFound = false;

        $returnValue = null;

        foreach (self::$routes as $route) {
            // If the method matches check the path

            // Add basePath to matching string
            if ($basePath != '' && $basePath != '/') {
                $route['expression'] = '('.$basePath.')'.$route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^'.$route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'].'$';

            // Check path match
            if (preg_match('#'.$route['expression'].'#'.($caseMatters ? '' : 'i').'u', $path, $matches)) {
                
                $pathMatchFound = true;

                // Cast allowed method to array if it's not one already, then run through all methods
                foreach ((array)$route['method'] as $allowedMethod) {
                    // Check method match
                    if (strtolower($method) == strtolower($allowedMethod)) {
                        array_shift($matches); // Always remove first element. This contains the whole string

                        if ($basePath != '' && $basePath != '/') {
                            array_shift($matches); // Remove basePath
                        }

                        if (gettype($route['function']) == 'object') {
                            //anonymous function in router
                            $returnValue = call_user_func_array($route['function'], $matches);
                        } else if (gettype($route['function']) == 'array') {
                            
                            // Auto dependency injection from router to controller 

                            $reflectionMethod = new \ReflectionMethod(new $route['function'][0], $route['function'][1]);
                            $params = [];

                            // if the method of the controller has parameter
                            if (count($reflectionMethod->getParameters()) > 0) {
                                                            
                                foreach ($reflectionMethod->getParameters() as $parameter) {
                                    if ($parameter->getType()) {
                                        array_push($params, (new ((string) $parameter->getType())));
                                    } else {

                                        array_push($params, $matches[0]);
                                        unset($matches[0]);

                                        $matches = array_values($matches);
                                    }
                                }
                            }
                            
                            $returnValue = call_user_func_array(array((new $route['function'][0]), $route['function'][1]), $params);
                        }

                        $routeMatchFound = true;
                        
                        // Do not check other routes
                        break;
                    }
                }
            }

            // Break the loop if the first found route is a match
            if ($routeMatchFound && !$multiMatch) {
                break;
            }
        }

        // No matching route was found
        if (!$routeMatchFound) {
            // But a matching path exists
            if ($pathMatchFound) {
                if (self::$methodNotAllowed) {
                    $returnValue = call_user_func_array(self::$methodNotAllowed, Array($path,$method));
                }
            } else {
                if (self::$pathNotFound) {
                    $returnValue = call_user_func_array(self::$pathNotFound, Array($path));
                }
            }
        }

        return $returnValue;
    }
}