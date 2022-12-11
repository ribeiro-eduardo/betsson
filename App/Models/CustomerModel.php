<?php

namespace App\Models;

final class CustomerModel
{
    private $id;
    private $first_name;
    private $last_name;
    private $gender;
    private $country;
    private $email;

    public function getId()
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }
    
    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }
    
    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;
    }

    public function getGender()
    {
        return $this->gender;
    }
    
    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    public function getCountry()
    {
        return $this->country;
    }
    
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

}