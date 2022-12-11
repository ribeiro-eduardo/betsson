<?php

namespace App\DAO;

use Exception;

class CustomerDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getAllCustomers()
    {
        $customers = $this->pdo
            ->query('SELECT * FROM customer')
            ->fetchAll(\PDO::FETCH_ASSOC);

            echo '<pre>';
            foreach ($customers as $customer) {
                var_dump($customer);
            }
            echo '</pre>';
            die;
    }

    public function addNewCustomer(\App\Models\CustomerModel $customer)
    {
        // echo '<pre>';
        // var_dump($customer);
        // echo '</pre>';

        $statement = $this->pdo
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

        return $this->pdo->lastInsertId();
    }

    public function updateCustomer(\App\Models\CustomerModel $customer)
    {
        try {
            $statement = $this->pdo
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