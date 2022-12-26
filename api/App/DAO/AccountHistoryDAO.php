<?php

namespace App\DAO;

class AccountHistoryDAO extends Connection
{
    public function __construct() {}

    public function getAccountHistory(\App\Models\AccountHistoryModel $accountHistory)
    {
        $accountId = $accountHistory->getAccountId();
        $statement = self::getPdo()->prepare('
            SELECT * 
            FROM account_history 
            WHERE account_id=:accountId
            AND operation IN (:operation)');

        $operation = $accountHistory->getOperation() ?? "'deposit', 'withdrawal'";
        $statement->execute(['accountId' => $accountId, 'operation' => $operation]); 
        
        $data = $statement->fetchAll();

        return $data;
    }

    public function addNewAccountHistory(\App\Models\AccountHistoryModel $accountHistory)
    {
        try {
            $statement = self::getPdo()
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

            return true;
        } catch (\PDOException $e) {
            return false;
        }
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

        $statement = self::getPdo()->prepare($sql);
        $statement->execute();

        $data = $statement->fetch(\PDO::FETCH_ASSOC);

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
        
        $statement = self::getPdo()->prepare($sql);
        $statement->execute();

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }
}