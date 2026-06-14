# PROJECT_MAP — EduCP Manager / CPManager

Pemetaan struktur folder, tanggung jawab tiap layer, dan status file saat audit baseline Fase 00.

## Layer & Tanggung Jawab

### 1. `public/` (Webroot & Entry Point)
- **`index.php`**: Berfungsi sebagai *Front Controller* tunggal. Menginisialisasi session, memuat helper global dan komponen core, mendaftarkan route, dan melakukan routing via `Router`.
- **`assets/`**: Menyimpan static files (assets) publik seperti CSS (`assets/css/app.css`), JS, dan gambar.
- **`uploads/`**: Folder tujuan untuk penyimpanan berkas yang diunggah oleh user (seperti file logo sekolah).

### 2. `app/core/` (Framework Engine)
- **`Router.php`**: Router sederhana berbasis query parameter `?page=<modul>_<aksi>`. Memetakan rute ke controller, method, dan memuat berkas controller secara dinamis.
- **`Auth.php`**: Logika otentikasi dan otorisasi berbasis Role-Based Access Control (RBAC). Menyediakan method `Auth::require()` untuk proteksi akses route.
- **`Session.php`**: Wrapper untuk manipulasi `$_SESSION` dan pengerasan keamanan session (seperti regenerasi ID sesi).
- **`Csrf.php`**: Penanganan token CSRF untuk mencegah serangan Cross-Site Request Forgery pada semua request POST.
- **`Database.php`**: Singleton/konektor PDO tunggal menggunakan variabel lingkungan. Menonaktifkan `PDO::ATTR_EMULATE_PREPARES` untuk keamanan prepared statements.
- **`helpers.php`**: Fungsi utility global. Berisi fungsi `e()` untuk HTML escape (proteksi XSS), `redirect_to()` untuk navigasi, dan `view()` untuk merender layout.

### 3. `app/controllers/` (Application Controller)
- **`AuthController.php`**: Menangani alur login (tampilan & submit) dan logout.
- **`DashboardController.php`**: Menangani halaman beranda untuk user yang terotentikasi.
- *Status*: Baru terdapat 2 controller dasar. Controller lain untuk modul akademik belum ada.

### 4. `app/models/` (Data Access Layer)
- *Status*: **Kosong**. Akses database saat ini belum melalui class model karena fungsionalitas CRUD data master belum diimplementasikan. Semua query PDO prepared statement nantinya diletakkan di layer ini.

### 5. `app/views/` (Presentation Layer)
- **`auth/login.php`**: Tampilan form login.
- **`layouts/`**: Template bersama seperti `header.php` dan `footer.php`.
- **`errors/`**: Halaman penanganan error HTTP (`403.php`, `404.php`, `500.php`).
- **`dashboard.php`**: Tampilan dashboard utama.
- *Status*: Subfolder `admin/`, `guru/`, `siswa/`, dan `print/` masih **kosong**.

### 6. `app/services/` (Business Services Layer)
- *Status*: **Kosong**. Dipersiapkan untuk menampung class helper bisnis seperti `CsvImporter` dan `AiProvider` di fase-fase berikutnya.

### 7. `database/` (Database Definition)
- **`schema.sql`**: Skema basis data awal yang mendefinisikan tabel `users`, `school_profile`, dan `academic_years`.
- **`seed.sql`**: Data uji coba awal (seeding) untuk admin, sekolah, dan tahun ajaran ganjil 2025/2026.
- **`migrations/`**: Folder untuk berkas migrasi SQL berurutan (saat ini kosong, hanya `.gitkeep` & `README.md`).

### 8. `storage/` (Private Files & Logs)
- **`logs/`**: Menyimpan berkas log kesalahan atau pelacakan sistem di luar webroot.
- **`documents/`**: Menyimpan berkas dokumen kurikulum (PDF/CSV/dll.) secara privat untuk alasan keamanan.

## Catatan Audit Fase 00
- Struktur aplikasi mengikuti arsitektur **PHP Native MVC** yang sangat rapi dan konsisten dengan routing tunggal `index.php?page=...`.
- Tidak ditemukan library/framework eksternal, menjaga aplikasi tetap ringan dan sesuai batasan CONVENTIONS.md.

