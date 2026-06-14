<?php
declare(strict_types=1);

/** Auth & RBAC minimal (Fase 2 menetapkan pola guard standar). */
class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    /** Wajib dipanggil di awal controller. */
    public static function require(string ...$roles): void
    {
        if (!self::check()) {
            redirect_to('login');
        }
        if ($roles && !in_array(self::role(), $roles, true)) {
            http_response_code(403);
            require BASE_PATH . '/app/views/errors/403.php';
            exit;
        }
    }
}
