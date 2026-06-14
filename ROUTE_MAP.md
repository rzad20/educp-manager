# ROUTE_MAP — EduCP Manager / CPManager

Daftar pemetaan rute aplikasi dari parameter `?page=...` ke Controller, Method, View, dan batasan Hak Akses (Role).

## Rute Terdaftar (Aktif di `public/index.php`)

| `page` | Controller | Method | View | Hak Akses (Role) | Catatan Keamanan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `login` | `AuthController` | `showLogin` | `auth/login` | Public (Semua) | Memuat form login |
| `login_submit` | `AuthController` | `login` | - | Public (Semua) | POST. Memvalidasi CSRF token. Langsung redirect ke dashboard tanpa validasi password |
| `logout` | `AuthController` | `logout` | - | Semua (Telah login) | Menghancurkan session dan redirect ke `login` |
| `dashboard` | `DashboardController` | `index` | `dashboard` | `admin`, `guru`, `siswa` | Diperiksa oleh `Auth::require()`. Menampilkan salam pembuka |

## Rute Belum Sinkron / Belum Dibuat (Temuan Audit)

Berdasarkan `CONVENTIONS.md` dan kebutuhan modul di fase mendatang, rute-rute berikut belum didaftarkan di `public/index.php`:

| `page` (Rencana) | Controller Terkait | Method Rencana | View Rencana | Status Baseline |
| :--- | :--- | :--- | :--- | :--- |
| `teacher_material_update` | Belum ada | Belum ditentukan | Belum ada | **Belum diimplementasikan** (Fase 11) |
| `teacher_assignment_update` | Belum ada | Belum ditentukan | Belum ada | **Belum diimplementasikan** (Fase 12) |

### Catatan Audit untuk Fase 1 (Route/Controller/View Stabilize)
- Semua rute baru di masa mendatang harus didaftarkan di `public/index.php` menggunakan `$router->add()`.
- Setiap rute aksi mutasi data (POST) wajib memiliki token CSRF.
- Pastikan method controller yang dipanggil router eksis di kelas controller.

