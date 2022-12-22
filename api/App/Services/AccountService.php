<?php 

namespace App\Services;

use Exception;

class AccountService 
{
    public static function getAccountById(int $accountId)
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

    public static function editAccount(\App\Models\AccountModel $account, string $operation, float $amount)
    {
        $accountId = $account->getId();

        $accountDAO = new \App\DAO\AccountDAO();   

        if ($accountDAO->editAccount($account)) {
            $newAccountHistory = new \App\Models\AccountHistoryModel();
            
            $newAccountHistory->setAccountId($accountId);
            $newAccountHistory->setOperation($operation);
            $newAccountHistory->setAmount($amount);
            $newAccountHistory->setDateTime(date('Y-m-d H:i:s'));

            if (AccountHistoryService::addNewAccountHistory($newAccountHistory)) {
                return true;
            }
            return false;
        }
        return false;
    }

    public static function generateRandomBonus(): float
    {
        $minBonus = 5;
        $maxBonus = 20;
        
        $randomFloat = rand($minBonus, $maxBonus);

        return $randomFloat;
    }
}