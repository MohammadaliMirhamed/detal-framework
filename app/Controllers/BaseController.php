<?php

namespace App\Controllers;

use App\Configs\Response;

class BaseController
{
    /**
     * response handler
     * 
     * @return Response
     */
    public function response(array $body, int $code = Response::HTTP_OK): Response
    {
        $response = new Response();
        $status = ($code >= 200 and $code <= 399) ? 'success' : 'error';

        return $response->json([   
            'status' => $status,
            'data' => $body
        ])->status($code);
    }
}
