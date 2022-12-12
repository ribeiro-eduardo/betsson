<?php

namespace App\DAO;

use App\Models\AccountModel;
use Exception;

class AccountDAO extends Connection
{
    public function __construct() {}

    public function getAccount(int $accountId)
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM account WHERE id = ?');
        $stmt->execute([$accountId]); 
        $account = $stmt->fetch();

        $accountModel = new AccountModel();
        $accountModel->setId($accountId);
        $accountModel->setCustomerId($account['customer_id']);
        $accountModel->setBonus($account['bonus']);
        $accountModel->setBalance($account['balance']);
        $accountModel->setBonusBalance($account['bonus_balance']);

        return $accountModel;
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
        try {
            $statement = self::getPdo()
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

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
