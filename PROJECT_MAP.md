# PROJECT_MAP — EduCP Manager / CPManager

> Diisi pada Fase 0 (Gemini). Petakan struktur folder & tanggung jawab tiap layer.

## Layer
- public/            : entry point (index.php), assets publik, uploads.
- app/core/          : Router, Auth, Session, Csrf, error handler, helper.
- app/controllers/   : controller per modul (validasi role di awal).
- app/models/        : akses data via PDO prepared statement.
- app/views/         : tampilan per role + layouts + errors + print.
- app/services/      : layanan lintas modul (CsvImporter, AiProvider, dll).
- database/          : schema.sql, seed.sql, migrations/.
- storage/           : log internal & dokumen (di luar webroot).

## Catatan
- (perlu verifikasi) Lengkapi saat audit Fase 0.
