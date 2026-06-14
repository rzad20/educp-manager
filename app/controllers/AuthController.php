<?php
declare(strict_types=1);

/**
 * AuthController — Login, Logout, Session Management
 *
 * Security Features (Fase 2):
 * - CSRF validation di middleware (public/index.php)
 * - Session regeneration on login (session fixation prevention)
 * - Password verification via User model
 * - Rate limiting via login attempt tracking
 * - Secure redirect after login
 */
class AuthController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 900; // 15 menit

    /** Tampilkan halaman login. */
    public function showLogin(): void
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            redirect_to('dashboard');
            return;
        }

        view('auth/login', ['title' => 'Login']);
    }

    /**
     * Proses login.
     *
     * SECURITY:
     * - CSRF validation di handle oleh middleware
     * - Session di-regenerate setelah login BERHASIL
     * - Login attempt di-track untuk rate limiting
     */
    public function login(): void
    {
        // Rate limiting check
        if ($this->isRateLimited()) {
            $_SESSION['_flash']['error'] = 'Terlalu banyak percobaan login. Coba lagi dalam beberapa menit.';
            redirect_to('login');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input
        if ($username === '' || $password === '') {
            $this->recordFailedAttempt();
            $_SESSION['_flash']['error'] = 'Username dan password wajib diisi.';
            redirect_to('login');
            return;
        }

        // Validasi panjang input (prevent DoS)
        if (strlen($username) > 60 || strlen($password) > 128) {
            $this->recordFailedAttempt();
            $_SESSION['_flash']['error'] = 'Login gagal.';
            redirect_to('login');
            return;
        }

        // Autentikasi user
        $userModel = new User();
        $user = $userModel->authenticate($username, $password);

        if (!$user) {
            $this->recordFailedAttempt();
            $_SESSION['_flash']['error'] = 'Username atau password salah.';
            redirect_to('login');
            return;
        }

        // Login BERHASIL
        $this->clearFailedAttempts();

        // Regenerate session ID untuk prevent session fixation
        Session::regenerate();

        // Simpan data user ke session (tanpa password)
        Session::set('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
            'full_name'=> $user['full_name'],
            'role'     => $user['role'],
        ]);

        // Redirect ke dashboard
        redirect_to('dashboard');
    }

    /**
     * Logout — hancurkan session.
     *
     * SECURITY:
     * - Session di-destroy untuk clear semua data
     * - Session cookie di-clear
     * - CSRF validation di handle oleh middleware
     */
    public function logout(): void
    {
        // Clear session secara menyeluruh
        $_SESSION = [];

        // Hapus session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        redirect_to('login');
    }

    /**
     * Cek apakah IP saat ini di-rate limit.
     */
    private function isRateLimited(): bool
    {
        $ip = $this->getClientIp();
        $attempts = Session::get("login_attempts_{$ip}", []);

        // Filter attempts yang masih dalam durasi lockout
        $attempts = array_filter($attempts, function ($timestamp) {
            return (time() - $timestamp) < self::LOCKOUT_DURATION;
        });

        if (count($attempts) >= self::MAX_LOGIN_ATTEMPTS) {
            return true;
        }

        return false;
    }

    /**
     * Record failed login attempt.
     */
    private function recordFailedAttempt(): void
    {
        $ip = $this->getClientIp();
        $attempts = Session::get("login_attempts_{$ip}", []);
        $attempts[] = time();
        Session::set("login_attempts_{$ip}", $attempts);
    }

    /**
     * Clear failed login attempts setelah login sukses.
     */
    private function clearFailedAttempts(): void
    {
        $ip = $this->getClientIp();
        Session::forget("login_attempts_{$ip}");
    }

    /**
     * Get client IP dengan proteksi spoofing.
     */
    private function getClientIp(): string
    {
        // Cek proxy headers hanya jika APP_ENV = production
        $isProduction = (getenv('APP_ENV') === 'production' || getenv('APP_ENV') === 'prod');

        if ($isProduction && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Di production, X-Forwarded-For mungkin di-set oleh load balancer
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
