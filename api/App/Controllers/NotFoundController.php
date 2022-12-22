<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NotFoundController
{
    public function throwNotFoundRoute(Request $request, Response $response)
    {
        $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();

        $body->write(json_encode([
            'status'  => 404,
            'message' => 'No routes found.'
        ]));
        return $response;
    }
}