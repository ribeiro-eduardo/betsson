<?php

namespace App\Controllers;

use App\DAO\CustomerDAO;
use App\Models\CustomerModel;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

final class CustomerController
{
    public function getCustomers(Request $request, Response $response, array $args): Response
    {
        // $response->getBody()->write('Customers list');

        $customerDAO = new CustomerDAO();
        $customerDAO->getAllCustomers();
        return $response;
    }

    public function addNewCustomer(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody());
        // echo '<pre>';
        // var_dump($data->first_name);
        // echo '</pre>';
        // die;
        $newCustomer = new CustomerModel();
        try {
            $newCustomer->setFirstName($data->first_name);
            $newCustomer->setLastName($data->last_name);
            $newCustomer->setGender($data->gender);
            $newCustomer->setCountry($data->country);
            $newCustomer->setEmail($data->email);

            $customerDAO = new CustomerDAO();
            $newCustomerId = $customerDAO->addNewCustomer($newCustomer);
             
            $newAccount = AccountController::addNewAccount($newCustomerId);

            $response->withHeader('Content-Type', 'application/json');
            $body = $response->getBody();

            $body->write(json_encode([
                'status'  => 200,
                'message' => 'Customer added successfully!'
            ]));
        } catch (Exception $e) {
            $response->withHeader('Content-Type', 'application/json');
            $body = $response->getBody();

            $body->write(json_encode([
                'status'  => $response->getStatusCode(),
                'message' => $e->getMessage()
            ]));
        }

        return $response;
    }

    public function editCustomer(Request $request, Response $response, array $args): Response
    {
        $data = json_decode($request->getBody());

        $customer = new CustomerModel();
        $customerDAO = new CustomerDAO();

        if (isset($args['customer_id'])) {
            $customer->setId(intval($args['customer_id']));
        }

        if (isset($data->first_name)) {
            $customer->setFirstName($data->first_name);
        }
        if (isset($data->last_name)) {
            $customer->setLastName($data->last_name);
        }
        if (isset($data->gender)) {
            $customer->setGender($data->gender);
        }
        if (isset($data->country)) {
            $customer->setCountry($data->country);
        }
        if (isset($data->email)) {
            $customer->setEmail($data->email);
        }

        $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();
        
        $updatedReturn = $customerDAO->updateCustomer($customer);
        if ($updatedReturn) {
            $body->write(json_encode([
                'message' => 'Customer edited successfully!'
            ]));
        } else {
            $body->write(json_encode([
                'message' => $updatedReturn
            ]));
        }

        return $response;
    }
}
