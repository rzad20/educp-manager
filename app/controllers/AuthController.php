<?php
declare(strict_types=1);

/** Controller auth contoh (Fase 2 mengeraskan login & CSRF). */
class AuthController
{
    public function showLogin(): void
    {
        view('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'CSRF token tidak valid.';
            return;
        }
        // TODO (Fase 2): verifikasi kredensial via model + password_verify().
        Session::regenerate();
        redirect_to('dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        redirect_to('login');
    }
}
