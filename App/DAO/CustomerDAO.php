<?php

namespace App\DAO;

use Exception;

class CustomerDAO extends Connection
{
    public function __construct() {}


    public function getCustomersById(int $id)
    {
        $customer = self::getPdo()
            ->query('SELECT * FROM customer WHERE id = ' . $id)
            ->fetch(\PDO::FETCH_ASSOC);

            return $customer;
    }

    public function getAllCustomers()
    {
        $customers = self::getPdo()
            ->query('SELECT * FROM customer')
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $customers;
    }

    public function addNewCustomer(\App\Models\CustomerModel $customer)
    {
        $statement = self::getPdo()
            ->prepare('INSERT INTO customer VALUES(
                null,
                :first_name,
                :last_name,
                :gender,
                :country,
                :email
            );');
        $statement->execute([
            'first_name' => $customer->getFirstName(),
            'last_name'  => $customer->getLastName(),
            'gender'     => $customer->getGender(),
            'country'    => $customer->getCountry(),
            'email'      => $customer->getEmail()
        ]);

        return self::getPdo()->lastInsertId();
    }

    public function updateCustomer(\App\Models\CustomerModel $customer)
    {
        try {
            $statement = self::getPdo()
                ->prepare('UPDATE customer SET
                        first_name = :first_name,
                        last_name = :last_name,
                        gender = :gender,
                        country = :country,
                        email = :email
                    WHERE
                        id = :id
                ;');
            $statement->execute([
                'first_name' => $customer->getFirstName(),
                'last_name'  => $customer->getLastName(),
                'gender'     => $customer->getGender(),
                'country'    => $customer->getCountry(),
                'email'      => $customer->getEmail(),
                'id'         => $customer->getId()
            ]);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}