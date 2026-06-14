# BUG_CANDIDATES — EduCP Manager / CPManager

Daftar temuan audit baseline, dugaan bug, ketidaksinkronan rute, dan kelemahan keamanan di Fase 00.

---

## 1. Bypass Otentikasi Mutlak di Form Login
- **Lokasi Berkas**: [AuthController.php](file:///d:/XAMPP/htdocs/educp-manager/app/controllers/AuthController.php#L12-L22)
- **Tingkat Keparahan**: **CRITICAL (Keamanan)**
- **Deskripsi**:
  Method `login()` saat ini hanya memvalidasi token CSRF. Jika token CSRF valid, method langsung memanggil `Session::regenerate()` dan melakukan redirect ke `dashboard`. Tidak ada pemeriksaan username atau pencocokan hash password (menggunakan `password_verify`). Data sesi pengguna (`$_SESSION['user']`) juga tidak pernah diset ke session.
- **Dampak**: 
  Siapa pun dapat men-submit form login dengan username dan password apa saja (atau kosong) dan lolos dari validasi form awal. Namun, karena data sesi `$_SESSION['user']` kosong, user akan langsung di-redirect kembali ke login di dashboard.

---

## 2. Siklus Redirect Tak Berujung (Infinite Redirect Loop)
- **Lokasi Berkas**: 
  - [DashboardController.php](file:///d:/XAMPP/htdocs/educp-manager/app/controllers/DashboardController.php#L7-L11)
  - [Auth.php](file:///d:/XAMPP/htdocs/educp-manager/app/core/Auth.php#L23-L33)
- **Tingkat Keparahan**: **HIGH (Fungsional)**
- **Deskripsi**:
  Di `DashboardController::index()`, terdapat proteksi role `Auth::require('admin', 'guru', 'siswa')`. Fungsi `Auth::require()` memanggil `Auth::check()` yang memeriksa eksistensi `$_SESSION['user']`.
  Karena `AuthController::login()` langsung mengalihkan (redirect) user ke dashboard tanpa mengisi data `$_SESSION['user']`, maka setibanya di dashboard, `Auth::require()` mendeteksi user belum login dan me-redirect user kembali ke `login`.
- **Dampak**:
  Pengguna tidak akan pernah bisa mengakses dashboard dan tertahan di halaman login tanpa ada pesan kesalahan.

---

## 3. Rute Akademik & View Terkait Belum Sinkron (Yatim)
- **Lokasi Berkas**:
  - [index.php](file:///d:/XAMPP/htdocs/educp-manager/public/index.php#L28-L34)
  - Direktori [app/views/guru/](file:///d:/XAMPP/htdocs/educp-manager/app/views/guru)
- **Tingkat Keparahan**: **MEDIUM (Fungsional)**
- **Deskripsi**:
  Di `CONVENTIONS.md`, dicatat rute `teacher_material_update` dan `teacher_assignment_update` untuk diperiksa secara khusus. Namun, rute-rute ini tidak terdaftar di router `public/index.php`. Selain itu, kelas controller yang bertanggung jawab menangani modul guru/akademik belum dibuat, dan subdirektori views seperti `admin/`, `guru/`, dan `siswa/` masih kosong (view yatim).
- **Dampak**:
  Aplikasi belum memiliki fungsionalitas akademik dasar selain login & dashboard contoh.

---

## 4. Kelemahan Konfigurasi Keamanan Session
- **Lokasi Berkas**: [index.php](file:///d:/XAMPP/htdocs/educp-manager/public/index.php#L9)
- **Tingkat Keparahan**: **MEDIUM (Keamanan)**
- **Deskripsi**:
  Pemanggilan `session_start()` dilakukan secara polos tanpa parameter keamanan cookie sesi.
- **Rekomendasi**:
  Pada Fase 2, pemanggilan sesi harus diatur agar menggunakan cookie secure untuk mencegah kebocoran sesi lewat serangan XSS/hijacking:
  ```php
  session_start([
      'cookie_httponly' => true,
      'cookie_secure'   => isset($_SERVER['HTTPS']), // Sesuai env
      'cookie_samesite' => 'Lax'
  ]);
  ```

---

## 5. Potensi Kebocoran Informasi Melalui Warning PHP Non-Fatal
- **Lokasi Berkas**: [index.php](file:///d:/XAMPP/htdocs/educp-manager/public/index.php#L18-L22)
- **Tingkat Keparahan**: **LOW (Keamanan / Informasi)**
- **Deskripsi**:
  Aplikasi mendaftarkan exception handler melalui `set_exception_handler()`. Namun, error PHP standar (seperti *Notice*, *Warning*, dan *Deprecated*) tidak ditangani secara khusus via `set_error_handler()`. Jika `display_errors` aktif di PHP.ini server lokal (XAMPP default), warning runtime biasa akan langsung tercetak di layar browser user.
- **Rekomendasi**:
  Daftarkan `set_error_handler()` untuk melempar `ErrorException` agar seluruh error non-fatal dikonversi menjadi Exception dan ditangkap secara aman oleh exception handler (Fase 3).

