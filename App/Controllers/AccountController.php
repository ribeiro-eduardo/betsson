<?php

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AccountController
{
    public function getAccount($accountId)
    {
        $accountDAO = new \App\DAO\AccountDAO();

        return $accountDAO->getAccount($accountId);
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

    public function deposit(Request $request, Response $response, array $args)
    {
        $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();
        
        $data = json_decode($request->getBody());
        $accountId = $args['accountId'];
        $amount = $data->amount;
 
        $depositOperation = 'deposit';
        $messageBonusAdded = '';

        $account = $this->getAccount($accountId);

        $accountHistoryDAO = new \App\Models\AccountHistoryModel();
        $accountHistoryDAO->setAccountId($accountId);
        $accountHistoryDAO->setOperation($depositOperation);
        
        $depositHistory = AccountHistoryController::getAccountHistory($accountId, $depositOperation);
        
        $newBalance = $account->getBalance() + $amount;
        $account->setBalance($newBalance);

        if ((count($depositHistory) + 1) % 3 == 0) {

            $bonus = $account->getBonus();
            $currentBonusBalance = $account->getBonusBalance();
            $newBonus = $amount * ($bonus / 100);
            $newBonusBalance = $currentBonusBalance + $newBonus;
            $account->setBonusBalance($newBonusBalance);

            $messageBonusAdded = 'As this is your third deposit in a row, you received a bonus of ' . $newBonus . ' EUR!';
        }

        if ($this->editAccount($account, $depositOperation, $amount)) {
            $body->write(json_encode([
                'status'  => 201,
                'message' => "$amount EUR deposited successfully! $messageBonusAdded"
            ]));
        }

        return $response;
    }

    public function withdraw(Request $request, Response $response, array $args)
    {
        $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();
        
        $data = json_decode($request->getBody());
        $accountId = $args['accountId'];
        $amount = $data->amount;
 
        $withdrawalOperation = 'withdrawal';

        $account = $this->getAccount($accountId);

        $accountHistoryDAO = new \App\Models\AccountHistoryModel();
        $accountHistoryDAO->setAccountId($accountId);
        $accountHistoryDAO->setOperation($withdrawalOperation);

        $currentBalance = $account->getBalance();
            
        if ($amount <= $currentBalance) {
            $newBalance = $currentBalance - $amount;
            $account->setBalance($newBalance);

            if ($this->editAccount($account, $withdrawalOperation, $amount)) {
                $body->write(json_encode([
                    'status'  => 201,
                    'message' => "$amount EUR withdrawn successfully!"
                ]));
            }
        } else {
            $body->write(json_encode([
                'status'  => 409,
                'message' => "Your current balance is $currentBalance EUR. It is not possible to withdraw $amount EUR."
            ]));
        }

        return $response;

    }

    public function editAccount(\App\Models\AccountModel $account, string $operation, float $amount)
    {
        $accountId = $account->getId();

        $accountDAO = new \App\DAO\AccountDAO();   

        if ($accountDAO->editAccount($account)) {
            $newAccountHistory = new \App\Models\AccountHistoryModel();
            $newAccountHistory->setAccountId($accountId);
            $newAccountHistory->setOperation($operation);
            $newAccountHistory->setAmount($amount);
            $newAccountHistory->setDateTime(date('Y-m-d H:i:s'));

            try {
                AccountHistoryController::addNewAccountHistory($newAccountHistory);
                return true;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public static function generateRandomBonus(): float
    {
        $minBonus = 5;
        $maxBonus = 20;
        
        $randomFloat = rand($minBonus, $maxBonus);

        return $randomFloat;
    }
}
