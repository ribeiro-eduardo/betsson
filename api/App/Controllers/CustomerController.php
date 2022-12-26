<?php

namespace App\Controllers;

use App\Services\CustomerService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class CustomerController
{
    public function getCustomers(Request $request, Response $response, array $args): Response
    {
        $body = $response->getBody();

        if (isset($args['customer_id'])) {
            $customer = \App\Services\CustomerService::getCustomerById($args['customer_id']);
            $body->write(json_encode($customer->toArray()));
        } else {
            $customers = \App\Services\CustomerService::getAllCustomers();
            $body->write(json_encode($customers));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function addNewCustomer(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody());
        $body = $response->getBody();

        if (!is_null($data)) {
            $returnAddNewCustomer = CustomerService::addNewCustomer($data);
            
            $body->write(json_encode($returnAddNewCustomer));
        } else {
            $body->write(json_encode([
                'status'  => 404,
                'message' => 'Missing body'
            ]));
        }
        return $response->withHeader('Content-Type', 'application/json');;
    }

    public function editCustomer(Request $request, Response $response, array $args): Response
    {
        $data = json_decode($request->getBody());
        $body = $response->getBody();

        if (!is_null($data)) {
            $returnEditCustomer = CustomerService::editCustomer($data, $args['customer_id']);

            $body->write(json_encode($returnEditCustomer));
        } else {
            $body->write(json_encode([
                'status'  => 409,
                'message' => 'Missing body'
            ]));
        }
        return $response->withHeader('Content-Type', 'application/json');;
    }
}
