<?php
declare(strict_types=1);

/**
 * User Model — Authentikasi & Akses Data User
 *
 * Security:
 * - Semua query pakai PDO prepared statement
 * - Password tidak pernah di-return
 * - Password hash menggunakan PASSWORD_DEFAULT (bcrypt)
 */
class User
{
    private ?PDO $pdo = null;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    /**
     * Autentikasi user berdasarkan username.
     *
     * @param string $username
     * @return array|null Data user (tanpa password) atau null jika gagal
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, username, password_hash, full_name, role, is_active
             FROM users
             WHERE username = :username AND is_active = 1
             LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // Jangan return password hash — pisahkan
        if (!$user) {
            return null;
        }

        // Simpan hash ke variabel temporary, return tanpa hash
        $hash = $user['password_hash'];
        unset($user['password_hash']);

        return $user;
    }

    /**
     * Verifikasi password terhadap hash tersimpan.
     *
     * @param string $plainPassword Password plain dari form
     * @param string $hash          Password hash dari DB
     * @return bool
     */
    public function verifyPassword(string $plainPassword, string $hash): bool
    {
        return password_verify($plainPassword, $hash);
    }

    /**
     * Hash password untuk pembuatan/update user.
     *
     * @param string $plainPassword
     * @return string Hashed password
     */
    public function hashPassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    /**
     * Get user berdasarkan ID.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, username, full_name, role, is_active, created_at
             FROM users
             WHERE id = :id AND is_active = 1
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Autentikasi user — mengembalikan user data jika kredensial valid.
     * Ini method utama untuk login.
     *
     * @param string $username
     * @param string $password Plain password dari form
     * @return array|null User data (tanpa password_hash) atau null
     */
    public function authenticate(string $username, string $password): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, username, password_hash, full_name, role, is_active
             FROM users
             WHERE username = :username AND is_active = 1
             LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            return null;
        }

        // Verifikasi password
        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        // Hapus password_hash sebelum return
        unset($user['password_hash']);

        return $user;
    }

    /**
     * Cek apakah user punya role tertentu.
     *
     * @param int    $userId
     * @param string ...$roles
     * @return bool
     */
    public function hasRole(int $userId, string ...$roles): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM users WHERE id = :id AND role IN (' .
            implode(',', array_fill(0, count($roles), '?')) .
            ') LIMIT 1'
        );
        $params = array_merge([':id' => $userId], $roles);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }
}