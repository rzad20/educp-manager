<?php
declare(strict_types=1);

/**
 * Token CSRF — WAJIB divalidasi di semua POST (Fase 2).
 *
 * Security:
 * - Token 32 bytes random (256-bit entropy)
 * - hash_equals() untuk timing-safe comparison (prevents timing attacks)
 * - Token di-regenerate setelah validasi berhasil
 */
class Csrf
{
    private const TOKEN_LENGTH = 32; // bytes

    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = self::generateToken();
        }
        return $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(self::token()) . '">';
    }

    /**
     * Validasi token CSRF.
     *
     * @param string|null $token Token dari request POST
     * @return bool True jika valid
     */
    public static function check(?string $token): bool
    {
        if (!is_string($token) || $token === '') {
            return false;
        }
        $stored = $_SESSION['_csrf'] ?? '';
        // hash_equals prevents timing attacks
        return hash_equals($stored, $token);
    }

    /**
     * Regenerate token CSRF SETELAH validasi berhasil.
     * Ini mencegah race condition dan double-submit.
     */
    public static function regenerate(): void
    {
        $_SESSION['_csrf'] = self::generateToken();
    }

    /**
     * Generate random token.
     */
    private static function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH));
    }

    /**
     * Get CSRF header name untuk AJAX requests.
     */
    public static function headerName(): string
    {
        return 'X-CSRF-TOKEN';
    }
}
