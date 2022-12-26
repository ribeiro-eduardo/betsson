<?php 

namespace App\Services;

class AccountHistoryService
{
    public static function registerOperation(\App\Models\AccountHistoryModel $accountHistoryModel)
    {
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();
        if ($accountHistoryDAO->addNewAccountHistory($accountHistoryModel)) {
            return true;
        }
        return false;
    }

    public static function getAccountHistory($accountId, $operation = null)
    {
        $accountHistoryModel = new \App\Models\AccountHistoryModel();
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();

        $accountHistoryModel->setAccountId($accountId);
        if ($operation) {
            $accountHistoryModel->setOperation($operation);
        }

        return $accountHistoryDAO->getAccountHistory($accountHistoryModel);
    }

    public static function getOperationsReport(int $interval)
    {
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();

        $operationDeposit    = 'deposit';
        $operationWithdrawal = 'withdrawal';

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
                'total_withdrawal_amount' => $withdrawalsReport ? (-$withdrawalsReport['total_withdrawal_amount']) : 0
            ];
        }
        return $arrayCompleteInfo;
    }
}