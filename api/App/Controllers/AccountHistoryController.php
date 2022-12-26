<?php

namespace App\Controllers;

use App\Services\AccountHistoryService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AccountHistoryController
{
    public function getOperationsReport(Request $request, Response $response, array $args)
    {
        $stdInterval = 7;
        $interval = $args['interval'] ?? $stdInterval;
        
        $operationsReport = AccountHistoryService::getOperationsReport($interval);
        $body = $response->getBody();

        $body->write(json_encode($operationsReport));
        return $response->withHeader('Content-Type', 'application/json');;
    }
}
