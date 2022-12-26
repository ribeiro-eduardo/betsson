<?php

namespace App\Controllers;

use App\Services\AccountService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AccountController
{
    public function getAccount($accountId)
    {
        $accountDAO = new \App\DAO\AccountDAO();
        return $accountDAO->getAccountById($accountId);
    }

    public function deposit(Request $request, Response $response, array $args)
    {
        $body = $response->getBody();
        
        $data = json_decode($request->getBody());

        if (empty($args)) {
            $body->write(json_encode([
                'status' => 400,
                'message' => 'Missing account info'
            ]));

            return $response;
        }
        $accountId = $args['accountId'];
        
        if (!empty($data)) {
            $body->write(json_encode(AccountService::deposit($data, $accountId)));
        } else {
            $body->write(json_encode([
                'status' => 400,
                'message' => 'Missing body.'
            ]));
        }

        return $response->withHeader('Content-Type', 'application/json');;
    }

    public function withdraw(Request $request, Response $response, array $args)
    {
        $body = $response->getBody();
        
        $data = json_decode($request->getBody());
        
        if (empty($args)) {
            $body->write(json_encode([
                'status' => 400,
                'message' => 'Missing account info'
            ]));

            return $response;
        }

        $accountId = $args['accountId'];
        
        if (!is_null($data)) {
            $body->write(json_encode(AccountService::withdraw($data, $accountId)));
        } else {
            $body->write(json_encode([
                'status' => 400,
                'message' => 'Missing body.'
            ]));
        }
        return $response->withHeader('Content-Type', 'application/json');;
    }    
}
