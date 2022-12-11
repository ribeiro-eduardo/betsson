<?php

namespace App\Controllers;

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
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();

        $stdInterval         = 7;
        $operationDeposit    = 'deposit';
        $operationWithdrawal = 'withdrawal';

        $interval = $args['interval'] ?? $stdInterval;

        $arrayUniqueCustomers = $accountHistoryDAO->getCountUniqueCustomersByCountry($interval);
        $arrayCompleteInfo = [];

        foreach ($arrayUniqueCustomers as $uniqueCustomers) {
            
            $depositsReport    = $accountHistoryDAO->getOperationsReport($operationDeposit, $uniqueCustomers['date'], $uniqueCustomers['country']);
            $withdrawalsReport = $accountHistoryDAO->getOperationsReport($operationWithdrawal, $uniqueCustomers['date'], $uniqueCustomers['country']);

            $arrayCompleteInfo[] = [
                'date' => $uniqueCustomers['date'],
                'country' => $uniqueCustomers['country'],
                'unique_customers' => $uniqueCustomers['unique_customers'],
                'number_deposits' => $depositsReport ? $depositsReport['number_deposits'] : 0,
                'total_deposit_amount' => $depositsReport ? $depositsReport['total_deposit_amount'] : 0,
                'number_withdrawals' => $withdrawalsReport ? $withdrawalsReport['number_withdrawals'] : 0,
                'total_withdrawal_amount' => $withdrawalsReport ? $withdrawalsReport['total_withdrawal_amount'] : 0
            ];
        }

        $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();

        $body->write(json_encode($arrayCompleteInfo));
        return $response;
    }
   
}
