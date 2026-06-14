# ROUTE_MAP — EduCP Manager / CPManager

Daftar pemetaan rute aplikasi dari parameter `?page=...` ke Controller, Method, View, dan batasan Hak Akses (Role).

## Rute Terdaftar (Aktif di `public/index.php`)

| `page` | Controller | Method | View | Hak Akses (Role) | Catatan Keamanan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `login` | `AuthController` | `showLogin` | `auth/login` | Public (Semua) | Memuat form login |
| `login_submit` | `AuthController` | `login` | - | Public (Semua) | POST. Memvalidasi CSRF token. Langsung redirect ke dashboard tanpa validasi password |
| `logout` | `AuthController` | `logout` | - | Semua (Telah login) | Menghancurkan session dan redirect ke `login` |
| `dashboard` | `DashboardController` | `index` | `dashboard` | `admin`, `guru`, `siswa` | Diperiksa oleh `Auth::require()`. Menampilkan salam pembuka |
| `teacher_material_update` | `TeacherController` | `materialUpdate` | `guru/material_update` | `guru` | Halaman penyambung; form dan persistensi dilanjutkan pada Fase 11 |
| `teacher_assignment_update` | `TeacherController` | `assignmentUpdate` | `guru/assignment_update` | `guru` | Halaman penyambung; form dan persistensi dilanjutkan pada Fase 12 |

## Rute yang Distabilkan pada Fase 1

- `teacher_material_update` dan `teacher_assignment_update` telah terhubung ke controller, method, dan view guru.
- Kedua halaman memakai guard role `guru`.
- Implementasi form dan persistensi data tetap berada pada Fase 11 dan Fase 12.

### Catatan Audit untuk Fase 1 (Route/Controller/View Stabilize)
- Semua rute baru di masa mendatang harus didaftarkan di `public/index.php` menggunakan `$router->add()`.
- Setiap rute aksi mutasi data (POST) wajib memiliki token CSRF.
- Router mengembalikan halaman 404 jika controller atau method route tidak tersedia.

