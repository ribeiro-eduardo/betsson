<?php

namespace App\Models;

use App\Services\AccountHistoryService;
use App\Services\AccountService;

final class AccountModel
{
    private $id;
    private $customer_id;
    private $bonus;
    private $balance;
    private $bonus_balance;

    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCustomerId(): int
    {
        return $this->customer_id;
    }
    
    public function setCustomerId(int $customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function getBonus(): float
    {
        return $this->bonus;
    }
    
    public function setBonus(float $bonus)
    {
        $this->bonus = $bonus;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
    
    public function setBalance(float $balance)
    {
        $this->balance = $balance;
    }

    public function getBonusBalance(): float
    {
        return $this->bonus_balance;
    }
    
    public function setBonusBalance(float $bonus_balance)
    {
        $this->bonus_balance = $bonus_balance;
    }

    public function registerOperation(\App\Models\AccountHistoryModel $accountHistoryModel)
    {   
        AccountHistoryService::registerOperation($accountHistoryModel);
    }

    public function depositsHistory()
    {
        $operation = 'deposit';
        return AccountHistoryService::getAccountHistory($this->getId(), $operation);
    }

    public function manageBalance(float $amount)
    {
        AccountService::manageBalance($this->getId(), $amount);
    }

    public function checkThirdDeposit(float $amount)
    {  
        $numberOfDepositsToEarnBonus = 3;
        $countDeposits = count($this->depositsHistory());

        if ($countDeposits > 0 && (count($this->depositsHistory()) % $numberOfDepositsToEarnBonus == 0)) {
            AccountService::manageBonus($this, $amount);
            return true;
        }

        return false;
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->getId(),
            'customer_id'   => $this->getCustomerId(),
            'bonus'         => $this->getBonus(),
            'balance'       => $this->getBalance(),
            'bonus_balance' => $this->getBonusBalance()
        ];
    }
}