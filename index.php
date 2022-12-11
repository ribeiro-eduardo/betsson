<?php
require __DIR__ . '/vendor/autoload.php';
require 'env.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/customers', App\Controllers\CustomerController::class . ':getCustomers');
$app->post('/customers', App\Controllers\CustomerController::class . ':addNewCustomer');
$app->put('/customers/{customer_id}', App\Controllers\CustomerController::class . ':editCustomer');


$app->put('/deposit/{accountId}', App\Controllers\AccountController::class . ':deposit');
$app->put('/withdrawal/{accountId}', App\Controllers\AccountController::class . ':withdraw');

$app->get('/operations_report[/{interval}]', App\Controllers\AccountHistoryController::class . ':getOperationsReport');

$app->run();