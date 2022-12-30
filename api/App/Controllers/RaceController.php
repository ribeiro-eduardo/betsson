<?php

namespace App\Controllers;

use App\Services\RaceService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RaceController
{
    public function getNextRaces(Request $request, Response $response, array $args)
    {
        $body = $response->getBody();
        $body->write(json_encode(RaceService::getNextRaces()));

        return $response->withHeader('Content-Type', 'application/json');
    }
}