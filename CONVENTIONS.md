# CONVENTIONS — EduCP Manager / CPManager

## 1. Arsitektur
- PHP Native MVC + MySQL. TIDAK memakai framework (Laravel/CI/Symfony) atau frontend SPA (React/Vue/Next).
- Routing tunggal: public/index.php?page=...
- Struktur folder WAJIB dipertahankan:
  - public/            -> entry point + assets publik
  - app/controllers/   -> controller per modul
  - app/models/        -> akses data (PDO)
  - app/views/         -> tampilan (admin/guru/siswa/print/errors/layouts)
  - app/core/          -> Auth, Session, Csrf, Router, Database, error handler, helper
  - app/services/      -> layanan (CsvImporter, AiProvider, dll)
  - public/assets/     -> css, js, img
  - database/          -> schema.sql, seed.sql, migrations/

## 2. Routing
- Tambah route baru lewat mekanisme ?page=... yang sudah ada; jangan membuat router baru.
- Nama page konsisten: <modul>_<aksi> (mis. teacher_material_update).
- Route di-register di public/index.php menggunakan `$router->add()`.

## 3. Controller
- Setiap controller WAJIB memvalidasi role (admin/guru/siswa) di awal.
- Controller tidak menulis SQL langsung; semua query lewat model.
- Guru hanya boleh mengakses data kelas/mapel yang diajar (scoping dari Fase 6).

## 4. Model
- Semua query memakai PDO prepared statement (tanpa string concatenation).
- Satu model = satu entitas/tabel utama.
- Password hash TIDAK boleh di-return ke view/controller; gunakan method terpisah.

## 5. View
- Semua output yang berasal dari user/DB WAJIB di-escape (`e()` helper).
- Tidak ada logika query di view.
- Session flash messages tampilkan dengan `e()` untuk XSS prevention.

## 6. Keamanan

### 6.1 Session Security
```php
// Konfigurasi cookie di public/index.php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $isProduction,   // HTTPS only di production
    'httponly' => true,            // JS tidak bisa akses cookie
    'samesite' => 'Strict',        // CSRF protection
]);
```
- **Regenerate session ID** saat login: `Session::regenerate()`
- **Regenerate periodik** setiap 15 menit untuk membatasi session hijacking window
- **Logout** harus hancurkan session + clear cookie

### 6.2 CSRF Protection
- **Semua POST** wajib memiliki dan divalidasi CSRF token
- Token di-generate via `Csrf::token()` atau `Csrf::field()` di form
- Validasi via `Csrf::check($token)` di middleware (public/index.php)
- Token di-regenerate SETELAH validasi berhasil (prevents double-submit)
- AJAX requests bisa kirim token via header `X-CSRF-TOKEN`

```html
<!-- Contoh form dengan CSRF -->
<form method="post" action="index.php?page=action">
    <?= Csrf::field() ?>
    <!-- fields lain -->
</form>
```

```javascript
// AJAX dengan CSRF header
fetch('/api', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
});
```

### 6.3 RBAC (Role-Based Access Control)

#### Role yang Tersedia
| Role   | Deskripsi                          |
|--------|------------------------------------|
| admin  | Administrator sistem               |
| guru   | Guru mata pelajaran                |
| siswa  | Siswa                              |

#### Matriks Akses Route
| Route                  | admin | guru | siswa | Catatan                          |
|:-----------------------|:-----:|:----:|:-----:|:---------------------------------|
| login                  | ✅    | ✅   | ✅    | Public (semua role termasuk anon)|
| logout                 | ✅    | ✅   | ✅    | Wajib login                      |
| dashboard              | ✅    | ✅   | ✅    | Semua role terautentikasi        |
| teacher_material_update| ❌    | ✅   | ❌    | Guru only                        |
| teacher_assignment_update| ❌  | ✅   | ❌    | Guru only                        |

#### Pola Guard RBAC
```php
// DI AWAL SETIAP CONTROLLER
class DashboardController
{
    public function index(): void
    {
        // Semua role terautentikasi
        Auth::require('admin', 'guru', 'siswa');
        view('dashboard', ['title' => 'Dashboard']);
    }
}

class TeacherController
{
    public function materialUpdate(): void
    {
        // HANYA guru
        Auth::require('guru');
        view('guru/material_update', ['title' => 'Perbarui Materi']);
    }
}
```

### 6.4 Rate Limiting Login
- Maksimum 5 percobaan login per IP dalam 15 menit
- Setelah lockout, tampilkan pesan umum (tidak disclose apakah username valid)

### 6.5 Password Security
- Hash dengan `password_hash($password, PASSWORD_DEFAULT)` (bcrypt)
- Verifikasi dengan `password_verify()`
- Jangan pernah tampilkan atau log password

### 6.6 Input Validation
- Trim whitespace dari input teks
- Validasi panjang input (username max 60, password max 128)
- Escape semua output dengan `e()` helper

### 6.7 Error Handling
- Error DB hanya di log internal; user melihat pesan umum
- HTTP 403 untuk akses ditolak (unauthorized role)
- HTTP 419 untuk CSRF validation failure
- API key & kredensial tidak pernah tampil di UI/log/output

## 7. Database
- Perubahan DB lewat migration SQL di database/migrations/ (penamaan: NNN_deskripsi.sql).
- schema.sql HANYA diubah pada fase DB yang disetujui Opus.

## 8. Commit & Branch
- Conventional Commits: feat/fix/refactor/docs/chore/test/security.
- 1 fase = 1 branch: feature/fase-XX-nama; rilis: release/vX.Y.Z.

## 9. Multi-Agent
- Opus: arsitektur, keamanan, DB besar, AI policy, final review.
- Gemini: audit, dokumentasi, laporan, import, QA, review konteks panjang.
- Codex: implementasi patch kode dari SPEC yang jelas.

## 10. Scope
- Tetap di scope EduCP Manager (Kurikulum Merdeka + e-learning ringan + AI Assistant).
- BUKAN Moodle, BUKAN ERP. Jangan rewrite total.
