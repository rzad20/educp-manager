# DEPLOYMENT — EduCP Manager / CPManager

## Persiapan
- PHP 8.x dengan ekstensi PDO MySQL aktif.
- MySQL/MariaDB.
- Document root diarahkan ke `public/` (jangan ke root repo).

## Langkah Deploy
1. Pull/clone kode ke server.
2. Buat database production dan user DB khusus.
3. Import `database/schema.sql`, lalu jalankan migrations di `database/migrations/` sesuai urutan (NNN_deskripsi.sql).
4. Set konfigurasi env production (DB, APP_ENV=production, APP_DEBUG=false).
5. Pastikan folder `storage/` & `public/uploads/` writable oleh web server.
6. Set permission file aman; folder `storage/` tidak boleh diakses publik.
7. Aktifkan HTTPS; cookie session pakai flag Secure.

## Pasca Deploy
- Verifikasi login semua role.
- Cek halaman error (403/404/500) tidak menampilkan stack trace.
- Backup database terjadwal.
