<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

$app = AppFactory::create();

$app->get('/', App\Controllers\NotFoundController::class . 'throwNotFoundRoute');
$app->post('/', App\Controllers\NotFoundController::class . 'throwNotFoundRoute');
$app->put('/', App\Controllers\NotFoundController::class . 'throwNotFoundRoute');

$app->get('/customers[/{customer_id}]', App\Controllers\CustomerController::class . ':getCustomers');
$app->get('/customers/', App\Controllers\CustomerController::class . ':getCustomers');

$app->post('/customers', App\Controllers\CustomerController::class . ':addNewCustomer');
$app->post('/customers/', App\Controllers\CustomerController::class . ':addNewCustomer');

$app->put('/customers[/{customer_id}]', App\Controllers\CustomerController::class . ':editCustomer');
$app->put('/customers/', App\Controllers\NotFoundController::class . ':throwNotFoundRoute');

$app->put('/deposit[/{accountId}]', App\Controllers\AccountController::class . ':deposit');
$app->put('/deposit/', App\Controllers\NotFoundController::class . ':throwNotFoundRoute');

$app->put('/withdrawal[/{accountId}]', App\Controllers\AccountController::class . ':withdraw');
$app->put('/withdrawal/', App\Controllers\NotFoundController::class . ':throwNotFoundRoute');

$app->get('/operations_report[/{interval}]', App\Controllers\AccountHistoryController::class . ':getOperationsReport');
$app->get('/operations_report/', App\Controllers\NotFoundController::class . ':throwNotFoundRoute');

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/next_races', App\Controllers\RaceController::class . ':getNextRaces');


$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});
$app->run();