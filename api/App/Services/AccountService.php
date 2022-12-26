<?php 

namespace App\Services;

class AccountService 
{
    public static function getAccountById(int $accountId): \App\Models\AccountModel
    {
        $accountDAO = new \App\DAO\AccountDAO();
        return $accountDAO->getAccountById($accountId);
    }

    public static function getAccountByCustomerId(int $customerId)
    {
        $accountDAO = new \App\DAO\AccountDAO();
        return $accountDAO->getAccountByCustomerId($customerId);
    }

    public static function addNewAccount(int $customerId)
    {
        $randomBonus = self::generateRandomBonus();
        
        $newAccount = new \App\Models\AccountModel();
        $newAccount->setCustomerId($customerId);
        $newAccount->setBonus($randomBonus);
        $newAccount->setBalance(0);
        $newAccount->setBonusBalance(0);

        $accountDAO = new \App\DAO\AccountDAO();
        $accountDAO->addNewAccount($newAccount);
    }

    public static function registerOperation(\App\Models\AccountModel $accountModel, \App\Models\AccountHistoryModel $accountHistoryModel)
    {
        $accountDAO = new \App\DAO\AccountDAO();   

        if ($accountDAO->editAccount($accountModel, $accountHistoryModel)) {
            return true;
        }
        return false;
    }

    public static function deposit(\stdClass $data, int $accountId)
    {
        $amount = $data->amount;

        if ($amount <= 0) {
            return [
                'status'  => 409,
                'message' => "Deposits should be greater than 0."
            ];
        }

        $depositOperation = 'deposit';
        $messageBonusAdded = '';
        $numberOfDepositsToEarnBonus = 3;

        $accountModel = self::getAccountById($accountId);

        $depositHistory = AccountHistoryService::getAccountHistory($accountId, $depositOperation);
        $newBalance = $accountModel->getBalance() + $amount;
        $accountModel->setBalance($newBalance);

        $accountHistoryModel = self::newAccountHistoryModel($accountId, $depositOperation, $amount);

        if ((count($depositHistory) + 1) % $numberOfDepositsToEarnBonus == 0) {

            $bonus = $accountModel->getBonus();
            $currentBonusBalance = $accountModel->getBonusBalance();
            $newBonus = $amount * ($bonus / 100);
            $newBonusBalance = $currentBonusBalance + $newBonus;
            $accountModel->setBonusBalance($newBonusBalance);

            $messageBonusAdded = 'As this is your third deposit in a row, you received a bonus of ' . $newBonus . ' EUR!';
        }

        if (self::registerOperation($accountModel, $accountHistoryModel)) {
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

        if ($amount <= 0) {
            return [
                'status'  => 409,
                'message' => "Withdrawals should be greater than 0."
            ];
        }

        $withdrawalOperation = 'withdrawal';
        $accountModel = AccountService::getAccountById($accountId);

        $currentBalance = $accountModel->getBalance();

        if ($amount <= $currentBalance) {
            $newBalance = $currentBalance - $amount;
            $accountModel->setBalance($newBalance);

            $accountHistoryModel = self::newAccountHistoryModel($accountId, $withdrawalOperation, $amount);

            if (self::registerOperation($accountModel, $accountHistoryModel)) {
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

    public static function newAccountHistoryModel(int $accountId, string $operation, float $amount)
    {
        $accountHistoryModel = new \App\Models\AccountHistoryModel();
        $accountHistoryModel->setAccountId($accountId);
        $accountHistoryModel->setOperation($operation);
        $accountHistoryModel->setAmount($amount);
        $accountHistoryModel->setDateTime(date('Y-m-d H:i:s'));

        return $accountHistoryModel;
    }

    public static function generateRandomBonus(): float
    {
        $minBonus = 5;
        $maxBonus = 20;
        
        $randomFloat = rand($minBonus, $maxBonus);

        return $randomFloat;
    }
}