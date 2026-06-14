<?php
declare(strict_types=1);

/** Token CSRF — WAJIB divalidasi di semua POST (Fase 2). */
class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function check(?string $token): bool
    {
        return is_string($token) && hash_equals($_SESSION['_csrf'] ?? '', $token);
    }
}
