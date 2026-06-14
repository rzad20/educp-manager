<?php
declare(strict_types=1);

/** Helper session minimal (Fase 2 mengeraskan session). */
class Session
{
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Panggil saat login untuk mencegah session fixation. */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }
}
