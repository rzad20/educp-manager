# RELEASE NOTES — EduCP Manager / CPManager v1.0.0

## Ringkasan
EduCP Manager v1.0.0 adalah rilis stabil pertama: aplikasi manajemen Kurikulum Merdeka
+ e-learning ringan + AI Assistant untuk admin, guru, dan siswa.

## Fitur Utama
- Manajemen sekolah: profil, tahun ajaran, semester aktif.
- Data master: user, guru, siswa, kelas, mata pelajaran.
- Kurikulum: CP Master, Elemen CP, TP, ATP, KKTP.
- E-learning: materi, tugas & pengumpulan, absensi, penilaian.
- Dokumen kurikulum & import CSV massal.
- Laporan siap cetak (ATP, KKTP, nilai, tugas, absensi).
- AI Assistant guru (draft perangkat ajar, tanpa auto-save ke DB).

## Catatan Upgrade / Instalasi
- Import database/schema.sql lalu jalankan migrations sesuai urutan.
- Salin ENV_EXAMPLE.md menjadi konfigurasi env; isi kredensial production.
- Pastikan APP_DEBUG=false di production.

## Keamanan
- RBAC per role, CSRF, prepared statement, escaping, session hardening, rate limit login.

## Known Issues
- (Isi bila ada catatan keterbatasan saat rilis.)
