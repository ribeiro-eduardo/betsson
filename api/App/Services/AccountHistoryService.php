<?php 

namespace App\Services;

class AccountHistoryService
{
    public static function addNewAccountHistory(\App\Models\AccountHistoryModel $accountHistoryModel)
    {
        $accountHistoryDAO = new \App\DAO\AccountHistoryDAO();
        if ($accountHistoryDAO->addNewAccountHistory($accountHistoryModel)) {
            return true;
        }
        return false;
    }
    
    public static function deposit(\stdClass $data, int $accountId)
    {
        $amount = $data->amount;
        $depositOperation = 'deposit';
        $messageBonusAdded = '';
        $numberOfDepositsToEarnBonus = 3;

        $account = AccountService::getAccountById($accountId);

        $accountHistoryDAO = new \App\Models\AccountHistoryModel();
        $accountHistoryDAO->setAccountId($accountId);
        $accountHistoryDAO->setOperation($depositOperation);

        $depositHistory = self::getAccountHistory($accountId, $depositOperation);
        
        $newBalance = $account->getBalance() + $amount;
        $account->setBalance($newBalance);

        if ((count($depositHistory) + 1) % $numberOfDepositsToEarnBonus == 0) {

            $bonus = $account->getBonus();
            $currentBonusBalance = $account->getBonusBalance();
            $newBonus = $amount * ($bonus / 100);
            $newBonusBalance = $currentBonusBalance + $newBonus;
            $account->setBonusBalance($newBonusBalance);

            $messageBonusAdded = 'As this is your third deposit in a row, you received a bonus of ' . $newBonus . ' EUR!';
        }

        if (AccountService::editAccount($account, $depositOperation, $amount)) {
            return [
                'status'  => 201,
                'message' => "$amount EUR deposited successfully! $messageBonusAdded"
            ];
        } else {
            return [
                'status'  => 409,
                'message' => "Error on depositing $amount EUR."
            ];
        }
    }

    public static function withdraw(\stdClass $data, int $accountId): array
    {
        $amount = $data->amount;
        $withdrawalOperation = 'withdrawal';
        $account = AccountService::getAccountById($accountId);

        $accountHistoryDAO = new \App\Models\AccountHistoryModel();
        $accountHistoryDAO->setAccountId($accountId);
        $accountHistoryDAO->setOperation($withdrawalOperation);

        $currentBalance = $account->getBalance();

        if ($amount <= $currentBalance) {
            $newBalance = $currentBalance - $amount;
            $account->setBalance($newBalance);

            if (AccountService::editAccount($account, $withdrawalOperation, $amount)) {
                return [
                    'status'  => 201,
                    'message' => "$amount EUR withdrawn successfully!"
                ];
            } else {
                return [
                    'status'  => 409,
                    'message' => "Error on withdrawing $amount EUR."
                ];
            }
        } else {
            return [
                'status'  => 409,
                'message' => "Your current balance is $currentBalance EUR. It is not possible to withdraw $amount EUR."
            ];
        }
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