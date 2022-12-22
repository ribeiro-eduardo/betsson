<?php

namespace App\Models;

use App\Services\AccountService;

final class CustomerModel
{
    private int $id;
    private string $first_name;
    private string $last_name;
    private string $gender;
    private string $country;
    private string $email;

    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }
    
    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }
    
    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }
    
    public function setGender(string $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
    
    public function setCountry(string $country)
    {
        $this->country = $country;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function getAccount(): \App\Models\AccountModel
    {
        return AccountService::getAccountByCustomerId($this->getId());
    }

    public function setAccount()
    {
        AccountService::addNewAccount($this->getId());
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name'  => $this->getLastName(),
            'gender'     => $this->getGender(),
            'country'    => $this->getCountry(),
            'email'      => $this->getEmail(),
            'account'    => json_encode($this->getAccount()->toArray())
        ];
    }
}