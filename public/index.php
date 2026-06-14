<?php
declare(strict_types=1);

/**
 * public/index.php — Single entry point (front controller)
 * Routing: public/index.php?page=<modul>_<aksi>
 */

session_start();

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/core/helpers.php';
require BASE_PATH . '/app/core/Session.php';
require BASE_PATH . '/app/core/Csrf.php';
require BASE_PATH . '/app/core/Auth.php';
require BASE_PATH . '/app/core/Router.php';

set_exception_handler(function (\Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    require BASE_PATH . '/app/views/errors/500.php';
});

$page = isset($_GET['page']) ? (string) $_GET['page'] : 'dashboard';

$router = new Router();

// Daftarkan route di sini (Fase 1 menstabilkan semua route).
$router->add('login', 'AuthController', 'showLogin');
$router->add('login_submit', 'AuthController', 'login');
$router->add('logout', 'AuthController', 'logout');
$router->add('dashboard', 'DashboardController', 'index');

$router->dispatch($page);
