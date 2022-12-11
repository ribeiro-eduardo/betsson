<?php

namespace App\Models;

final class AccountHistoryModel
{
    private $id;
    private $account_id;
    private $operation;
    private $amount;
    private $date_time;

    public function getId()
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getAccountId()
    {
        return $this->account_id;
    }
    
    public function setAccountId(int $accountId)
    {
        $this->account_id = $accountId;
    }

    public function getOperation()
    {
        return $this->operation;
    }
    
    public function setOperation(string $operation)
    {
        $this->operation = $operation;
    }

    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function getDateTime()
    {
        return $this->date_time;
    }

    public function setDateTime(string $dateTime)
    {
        $this->date_time = $dateTime;
    }

}