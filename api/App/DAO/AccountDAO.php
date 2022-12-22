<?php

namespace App\DAO;

use Error;
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

    public function editAccount(\App\Models\AccountModel $account)
    {
        $pdo = self::getPdo();

        $pdo->beginTransaction();

        try {
            $statement = $pdo
                ->prepare('
                    UPDATE account SET
                        balance = :balance,
                        bonus_balance = :bonus_balance
                    WHERE id = :id;');

            $statement->execute([
                'balance'       => $account->getBalance(),
                'bonus_balance' => $account->getBonusBalance(),
                'id'            => $account->getId()
            ]);

            $pdo->commit();
            return true;
        } catch (Error $e) {
            $pdo->rollBack();
            return false;
        }
    }
}
