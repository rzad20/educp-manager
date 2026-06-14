# EduCP Manager / CPManager

Aplikasi manajemen **Kurikulum Merdeka + e-learning ringan + AI Assistant**
(PHP Native MVC + MySQL). Tanpa framework, tanpa SPA.

## Setup lokal (XAMPP / Laragon)
1. Clone repository:
   ```bash
   git clone <REPO_URL> educp-manager
   cd educp-manager
   ```
2. Buat database MySQL, mis. `educp_manager`.
3. Import skema dan seed:
   ```bash
   mysql -u root -p educp_manager < database/schema.sql
   mysql -u root -p educp_manager < database/seed.sql
   ```
4. Salin `ENV_EXAMPLE.md` -> `.env` (atau `config/env.php`) lalu isi kredensial DB.
5. Arahkan document root web server ke folder `public/`.
6. Jalankan dev server bawaan PHP:
   ```bash
   php -S localhost:8000 -t public
   ```
7. Buka http://localhost:8000/?page=login

## Struktur
- `public/`            entry point + assets publik
- `app/controllers/`   controller per modul
- `app/models/`        akses data (PDO)
- `app/views/`         tampilan (admin/guru/siswa/print/errors/layouts)
- `app/core/`          Router, Auth, Session, Csrf, error handler, helper
- `app/services/`      layanan (CsvImporter, AiProvider, dll)
- `database/`          schema.sql, seed.sql, migrations/
- `storage/`           log internal & dokumen (di luar webroot)

## Konvensi
Baca `CONVENTIONS.md`, `AGENTS.md`, dan `PROGRESS.md` sebelum mulai bekerja.
