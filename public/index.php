<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Marius\BasicForm\Controllers\ContactController;
use Marius\BasicForm\Core\Router;
use Marius\BasicForm\Middleware\VerifyCsrf;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__. '/../');
$dotenv->load();

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

$router = new Router();
$router->get('/', [ContactController::class, 'index']);
$router->get('/api/entries', [ContactController::class, 'list']);
$router->post('/api/submit', [ContactController::class, 'store'], [VerifyCsrf::class]);
$router->resolve();
