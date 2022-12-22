<?php

namespace App\Controllers;

use App\Services\AccountHistoryService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AccountHistoryController
{
    public static function getAccountHistory($account_id, $operation = null)
    {
        $accountHistoryModel = new \App\Models\AccountHistoryModel();
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();

        $accountHistoryModel->setAccountId($account_id);
        if ($operation) {
            $accountHistoryModel->setOperation($operation);
        }

        return $accountHistoryDAO->getAccountHistory($accountHistoryModel);
    }

    public static function addNewAccountHistory(\App\Models\AccountHistoryModel $accountHistory)
    {
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();
        if ($accountHistoryDAO->addNewAccountHistory($accountHistory)) {
            return true;
        }
        return false;
    }

    public function getOperationsReport(Request $request, Response $response, array $args)
    {
        $stdInterval = 7;
        $interval = $args['interval'] ?? $stdInterval;
        
        $operationsReport = AccountHistoryService::getOperationsReport($interval);

        $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();

        $body->write(json_encode($operationsReport));
        return $response;
    }
}
