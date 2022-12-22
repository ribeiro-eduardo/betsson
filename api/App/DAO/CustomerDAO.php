<?php

namespace App\DAO;

use PDOException;
class CustomerDAO extends Connection
{
    public function __construct() {} 
    
    public function getCustomerById(int $customerId): \App\Models\CustomerModel
    {
        $statement = self::getPdo()->prepare('SELECT * FROM customer WHERE id = :customerId');
        $statement->bindParam(':customerId', $customerId, \PDO::PARAM_INT);
        $statement->execute();
        
        $customer = $statement->fetchObject(\App\Models\CustomerModel::class);
    
        return $customer;
    }

    public function getAllCustomers()
    {
        $customers = self::getPdo()
            ->query('SELECT * FROM customer')
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $customers;
    }

    public function addNewCustomer(\App\Models\CustomerModel $customer): bool | \App\Models\CustomerModel
    {
        $pdo = self::getPdo();
        $pdo->beginTransaction();

        $statement = $pdo
            ->prepare('INSERT INTO customer VALUES(
                null,
                :first_name,
                :last_name,
                :gender,
                :country,
                :email
            );');

        try {
            $statement->execute([
                'first_name' => $customer->getFirstName(),
                'last_name'  => $customer->getLastName(),
                'gender'     => $customer->getGender(),
                'country'    => $customer->getCountry(),
                'email'      => $customer->getEmail()
            ]);
            
            $newCustomerId = intval($pdo->lastInsertId());
            $newCustomer = $this->getCustomerById($newCustomerId);
            
            $newCustomer->setAccount();

            $pdo->commit();
        
            return $newCustomer;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function updateCustomer(\App\Models\CustomerModel $customer)
    {
        $pdo = self::getPdo();
        $pdo->beginTransaction();

        try {
            $statement = $pdo
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

            $editedCustomer = $this->getCustomerById($customer->getId());

            $pdo->commit();
            return $editedCustomer;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }
}