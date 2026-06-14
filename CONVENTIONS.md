# CONVENTIONS — EduCP Manager / CPManager

## 1. Arsitektur
- PHP Native MVC + MySQL. TIDAK memakai framework (Laravel/CI/Symfony) atau frontend SPA (React/Vue/Next).
- Routing tunggal: public/index.php?page=...
- Struktur folder WAJIB dipertahankan:
  - public/            -> entry point + assets publik
  - app/controllers/   -> controller per modul
  - app/models/        -> akses data (PDO)
  - app/views/         -> tampilan (admin/guru/siswa/print/errors/layouts)
  - app/core/          -> Auth, Session, Csrf, Router, error handler, helper
  - app/services/      -> layanan (CsvImporter, AiProvider, dll)
  - public/assets/     -> css, js, img
  - database/          -> schema.sql, seed.sql, migrations/

## 2. Routing
- Tambah route baru lewat mekanisme ?page=... yang sudah ada; jangan membuat router baru.
- Nama page konsisten: <modul>_<aksi> (mis. teacher_material_update).

## 3. Controller
- Setiap controller WAJIB memvalidasi role (admin/guru/siswa) di awal.
- Controller tidak menulis SQL langsung; semua query lewat model.
- Guru hanya boleh mengakses data kelas/mapel yang diajar (scoping dari Fase 6).

## 4. Model
- Semua query memakai PDO prepared statement (tanpa string concatenation).
- Satu model = satu entitas/tabel utama.

## 5. View
- Semua output yang berasal dari user/DB WAJIB di-escape (htmlspecialchars).
- Tidak ada logika query di view.

## 6. Keamanan
- Semua POST WAJIB memvalidasi token CSRF.
- Session: regenerate id saat login; cookie HttpOnly/SameSite/Secure sesuai env.
- Error DB hanya di log internal; user melihat pesan umum.
- API key & kredensial tidak pernah tampil di UI/log/output.

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
