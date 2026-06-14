<?php
declare(strict_types=1);

/**
 * public/index.php — Single entry point (front controller)
 * Routing: public/index.php?page=<modul>_<aksi>
 *
 * SECURITY HARDENING (Fase 2):
 * - Session cookie: HttpOnly/Secure/SameSite
 * - Session regeneration on role change
 * - CSRF guard middleware
 */

declare(strict_types=1);

// Konfigurasi session AMAN sebelum session_start()
$isProduction = (getenv('APP_ENV') === 'production' || getenv('APP_ENV') === 'prod');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $isProduction,   // HTTPS only di production
    'httponly' => true,            // JS tidak bisa akses cookie
    'samesite' => 'Strict',        // CSRF protection
]);

session_start();

// Regenerate session ID secara periodik (setiap 15 menit atau 10 request)
if (!isset($_SESSION['_last_regenerate'])) {
    $_SESSION['_last_regenerate'] = time();
}
$regenerateInterval = 900; // 15 menit
if (time() - $_SESSION['_last_regenerate'] > $regenerateInterval) {
    session_regenerate_id(true);
    $_SESSION['_last_regenerate'] = time();
}

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/core/helpers.php';
require BASE_PATH . '/app/core/Session.php';
require BASE_PATH . '/app/core/Csrf.php';
require BASE_PATH . '/app/core/Auth.php';
require BASE_PATH . '/app/core/Database.php';
require BASE_PATH . '/app/core/Router.php';

// Autoloader sederhana untuk models
spl_autoload_register(function (string $class): void {
    $modelFile = BASE_PATH . '/app/models/' . $class . '.php';
    if (is_file($modelFile)) {
        require $modelFile;
    }
});

// CSRF Guard Middleware — validasi untuk semua request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Daftar route yang TIDAK memerlukan CSRF (jika ada)
    $csrfExempt = ['api_webhook']; // tambahkan route yang dibebaskan jika perlu

    if (!in_array($page, $csrfExempt, true)) {
        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            // Jangan tampilkan pesan error detail ke user
            error_log("CSRF validation failed for page: {$page}");
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Token tidak valid. Silakan refresh halaman.']);
            } else {
                $_SESSION['_flash']['error'] = 'Token tidak valid. Silakan coba lagi.';
                redirect_to('login');
            }
            exit;
        }
        // Regenerate CSRF token SETELAH validasi berhasil (prevents double-submit)
        Csrf::regenerate();
    }
}

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
$router->add('teacher_material_update', 'TeacherController', 'materialUpdate');
$router->add('teacher_assignment_update', 'TeacherController', 'assignmentUpdate');

$router->dispatch($page);
