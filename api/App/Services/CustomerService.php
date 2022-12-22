<?php

namespace App\Services;

use AccountService;
use App\DAO\CustomerDAO;
use App\Models\CustomerModel;
use Exception;

class CustomerService
{   
    public static function getAllCustomers()
    {
        $customerDAO = new CustomerDAO;
        return $customerDAO->getAllCustomers();
    }

    public static function getCustomerById(int $customerId)
    {
        $customerDAO = new CustomerDAO;
        try {
            return $customerDAO->getCustomerById($customerId);
        } catch (Exception $e) {
            return json_encode([
                'status'  => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function addNewCustomer($data): array
    {
        $arrayRequiredParameters = ['first_name' => 'Field first_name not found!',
                                    'last_name'  => 'Field last_name not found!',
                                    'gender'     => 'Field gender not found!',
                                    'country'    => 'Field country not found!',
                                    'email'      => 'Field email not found!'];

        $customerModel = new CustomerModel();

        foreach ($arrayRequiredParameters as $key => $requiredParam) {
            if (!isset($data->$key)) {
                return ['status' => 409, 'message' => $requiredParam];
            }
        }

        $customerModel->setFirstName($data->first_name)
                      ->setLastName($data->last_name)
                      ->setGender($data->gender)
                      ->setCountry($data->country)
                      ->setEmail($data->email);
        
        $customerDAO = new CustomerDAO();
        
        return $customerDAO->addNewCustomer($customerModel)->toArray();
    }

    public static function editCustomer(\stdClass $data, int $customerId)
    {
        $arrayProperties = ['first_name' => 'setFirstName',
                            'last_name'  => 'setLastName',
                            'gender'     => 'setGender',
                            'country'    => 'setCountry',
                            'email'      => 'setEmail'];
                            
        $customerModel = self::getCustomerById($customerId);
        $customerDAO = new CustomerDAO();

        foreach ($arrayProperties as $key => $requiredParam) {
            if (isset($data->$key)) {
                $customerModel->$requiredParam($data->$key);
            }
        }
        
        return $customerDAO->updateCustomer($customerModel)->toArray();
    }
}