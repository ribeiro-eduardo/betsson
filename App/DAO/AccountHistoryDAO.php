<?php

namespace App\DAO;

use Exception;

class AccountHistoryDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAccountHistory(\App\Models\AccountHistoryModel $accountHistory)
    {
        $account_id = $accountHistory->getAccountId();
        $statement = $this->pdo->prepare('
            SELECT * 
            FROM account_history 
            WHERE account_id=:account_id
            AND operation IN (:operation)');

        
        $operation = $accountHistory->getOperation() ?? "'deposit', 'withdrawal'";
        $statement->execute(['account_id' => $account_id, 'operation' => $operation]); 

        // $statement->debugDumpParams();
        
        $data = $statement->fetchAll();

        return $data;
    }

    public function addNewAccountHistory(\App\Models\AccountHistoryModel $accountHistory)
    {
        $statement = $this->pdo
            ->prepare('INSERT INTO account_history (account_id, operation, amount, date_time) VALUES (
                :account_id,
                :operation,
                :amount,
                :date_time
            );');
        $statement->execute([
            'account_id' => $accountHistory->getAccountId(),
            'operation'  => $accountHistory->getOperation(),
            'amount'     => $accountHistory->getAmount(),
            'date_time'  => $accountHistory->getDateTime(),
        ]);
    }

    public function getOperationsReport(string $operation, string $date, string $country)
    {  
        $operationDeposit = 'deposit';

        if ($operation == $operationDeposit) {
            $aliasCountOperations = 'number_deposits';
            $aliasSumAmount       = 'total_deposit_amount';
        } else {
            $aliasCountOperations = 'number_withdrawals';
            $aliasSumAmount       = 'total_withdrawal_amount';
        }

        $sql = "SELECT 
                    DATE(date_time) date, 
                    customer.country,
                    -- COUNT(DISTINCT customer.id) AS unique_customers,
                    COUNT(account_history.id) AS $aliasCountOperations,
                    SUM(amount) AS $aliasSumAmount
                FROM account_history 
                INNER JOIN account ON (account_history.account_id = account.id) 
                INNER JOIN customer ON (account.customer_id = customer.id)
                WHERE operation = '$operation'
                    AND customer.country = '$country'
                    AND DATE(date_time) = '$date'
                GROUP BY date, country, operation 
                ORDER BY date";

        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        //  $statement->debugDumpParams();

        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        // echo '<pre>';
        // var_dump($data);
        // echo '<pre>';
        // die;
        return $data;
    }

    public function getCountUniqueCustomersByCountry(int $interval)
    {
        $sql = "SELECT 
                    DATE(date_time) date, 
                    COUNT(DISTINCT customer.id) AS unique_customers,
                    customer.country
                FROM account_history 
                INNER JOIN account ON (account_history.account_id = account.id)
                INNER JOIN customer ON (account.customer_id = customer.id)
                WHERE account_history.date_time >= (CURDATE() - INTERVAL {$interval} DAY)
                GROUP BY DATE(date_time), country 
                ORDER BY DATE(date_time)";
        
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

}
