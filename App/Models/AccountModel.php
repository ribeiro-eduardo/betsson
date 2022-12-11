<?php

namespace App\Models;

final class AccountModel
{
    private $id;
    private $customer_id;
    private $bonus;
    private $balance;
    private $bonus_balance;

    public function getId()
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }
    
    public function setCustomerId(int $customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function getBonus()
    {
        return $this->bonus;
    }
    
    public function setBonus(float $bonus)
    {
        $this->bonus = $bonus;
    }

    public function getBalance()
    {
        return $this->balance;
    }
    
    public function setBalance(float $balance)
    {
        $this->balance = $balance;
    }

    public function getBonusBalance()
    {
        return $this->bonus_balance;
    }
    
    public function setBonusBalance(float $bonus_balance)
    {
        $this->bonus_balance = $bonus_balance;
    }

}