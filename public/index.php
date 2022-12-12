<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = AppFactory::create();

$app->get('/', function(Request $request, Response $response) {
    $response->withHeader('Content-Type', 'application/json');
    $body = $response->getBody();

    $body->write(json_encode([
        'status'  => 404,
        'message' => 'No routes found.'
    ]));
    return $response;
});

$app->post('/', function(Request $request, Response $response) {
    $response->withHeader('Content-Type', 'application/json');
    $body = $response->getBody();

    $body->write(json_encode([
        'status'  => 404,
        'message' => 'No routes found.'
    ]));
    return $response;
});

$app->get('/customers[/{customer_id}]', App\Controllers\CustomerController::class . ':getCustomers');
$app->post('/customers', App\Controllers\CustomerController::class . ':addNewCustomer');
$app->put('/customers/{customer_id}', App\Controllers\CustomerController::class . ':editCustomer');


$app->put('/deposit/{accountId}', App\Controllers\AccountController::class . ':deposit');
$app->put('/withdrawal/{accountId}', App\Controllers\AccountController::class . ':withdraw');

$app->get('/operations_report[/{interval}]', App\Controllers\AccountHistoryController::class . ':getOperationsReport');

$app->run();