<?php

namespace App\DAO;

use App\Services\ErrorLogService;

class AccountDAO extends Connection
{
    public function __construct() {}

    public function getAccountById(int $accountId): \App\Models\AccountModel
    {
        $statement = self::getPdo()->prepare('SELECT * FROM account WHERE id = :accountId');
        $statement->bindParam(':accountId', $accountId, \PDO::PARAM_INT);
        $statement->execute();
        
        $account = $statement->fetchObject(\App\Models\AccountModel::class);

        return $account;
    }

    public function getAccountByCustomerId(int $customerId): \App\Models\AccountModel 
    {
        $statement = self::getPdo()->prepare('SELECT * FROM account WHERE customer_id = :customerId');
        $statement->bindParam(':customerId', $customerId, \PDO::PARAM_INT);
        $statement->execute();
        
        $account = $statement->fetchObject(\App\Models\AccountModel::class);

        return $account;
    }

    public function addNewAccount(\App\Models\AccountModel $account)
    {
        $statement = self::getPdo()
            ->prepare('INSERT INTO account VALUES (
                null,
                :customer_id,
                :bonus,
                :balance,
                :bonus_balance
            );');
        $statement->execute([
            'customer_id'   => $account->getCustomerId(),
            'bonus'         => $account->getBonus(),
            'balance'       => $account->getBalance(),
            'bonus_balance' => $account->getBonusBalance()
        ]);
    }

    public function editAccount(\App\Models\AccountModel $account, \App\Models\AccountHistoryModel $accountHistoryModel)
    {
        $pdo = self::getPdo();
        $pdo->beginTransaction();

        $amount = $accountHistoryModel->getAmount();

        try {
            $statement = $pdo
                ->prepare('
                    UPDATE account SET
                        bonus_balance = :bonus_balance
                    WHERE id = :id;');
      
            $statement->execute([
                'bonus_balance' => $account->getBonusBalance(),
                'id'            => $account->getId()
            ]);

            
            $account->registerOperation($accountHistoryModel);

            
            $pdo->commit();
            
            $account->manageBalance($amount);

            if ($account->checkThirdDeposit($amount)) {
                $newBonus = $amount * ($account->getBonus() / 100);
                $editAccountReturn = [
                    'status' => true,
                    'thirdDeposit' => true,
                    'newBonus' => $newBonus
                ];
            } else {
                $editAccountReturn = [
                    'status' => true,
                    'thirdDeposit' => false
                ];
            }

            return $editAccountReturn;

        } catch (\PDOException $e) {
            ErrorLogService::log($e->getMessage());
            $pdo->rollBack();
            return false;
        }
    }

    public static function manageBalance(\App\Models\AccountModel $account)
    {
        ErrorLogService::log('model: ' . json_encode($account->toArray()));
        $pdo = self::getPdo();
        $pdo->beginTransaction();

        try {
            $statement = $pdo
                ->prepare('UPDATE account SET
                        balance = :balance
                    WHERE id = :id;
                ');
          
            $statement->execute([
                'balance' => $account->getBalance(),
                'id'      => $account->getId()
            ]);
            
            $pdo->commit();
        } catch (\PDOException $e) {
            ErrorLogService::log($e->getMessage());
            return false;
        }
    }

    public static function manageBonus(\App\Models\AccountModel $account)
    {

        $pdo = self::getPdo();
        $pdo->beginTransaction();

        try {
            $statement = $pdo
                ->prepare('UPDATE account SET
                        bonus_balance = :bonus_balance
                    WHERE id = :id;
                ');
          
            $statement->execute([
                'bonus_balance' => $account->getBonusBalance(),
                'id'            => $account->getId()
            ]);
            
            $pdo->commit();
        } catch (\PDOException $e) {
            ErrorLogService::log($e->getMessage());
            return false;
        }
    }
}