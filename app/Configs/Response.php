<?php

namespace App\Configs;

use App\Foundations\Contracts\ResponseInterface;

class Response implements ResponseInterface
{

    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_SEE_OTHER = 303;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_USE_PROXY = 305;
    public const HTTP_RESERVED = 306;
    public const HTTP_TEMPORARY_REDIRECT = 307;
    public const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_TOO_MANY_REQUESTS = 429;    // RFC6585
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * Set body of response as text
     * 
     * @param mixed $data
     * @return ResponseInterface
     */
    public function body(mixed $data): ResponseInterface
    {
        echo $data;
        return $this;
    }
    
    /**
     * Set body of response as json
     * 
     * @param array $data
     * @return ResponseInterface
     */
    public function json(array $data): ResponseInterface
    {
        header('Content-type: application/json');
        echo json_encode($data);
        return $this;
    }

    /**
     * Set http code
     * 
     * @return ResponseInterface
     */
    public function status(int $code = 200): ResponseInterface
    {
        http_response_code($code);
        return $this;
    }
    
    /**
     * Set headers ruls
     * 
     * @param array $ruls
     * @return ResponseInterface
     */
    public function header(array $rules): ResponseInterface
    {
        foreach ($rules as $headerType => $headerValue) {
            header($headerType . ': ' . $headerValue);
        }

        return $this;
    }

    /**
     * Redirect route
     * 
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        header("location: " . $url);
    }
}