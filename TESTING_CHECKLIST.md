# TESTING CHECKLIST — EduCP Manager / CPManager

## Umum (semua role)
- [ ] Login/logout berfungsi; session aman; akses lintas-role ditolak.
- [ ] Semua form POST memiliki & memvalidasi token CSRF.
- [ ] Halaman 403/404/500 tampil rapi; tidak ada stack trace di production.

## Admin
- [ ] CRUD profil sekolah, tahun ajaran, semester aktif.
- [ ] CRUD user, guru, siswa, kelas, mapel (validasi unik berfungsi).
- [ ] CRUD CP, Elemen CP, TP; filter kurikulum/fase/mapel.
- [ ] Import CSV: template, preview, log per baris, cegah duplikasi.
- [ ] AI Settings & Knowledge Base aman (API key tidak bocor).

## Guru
- [ ] Hanya melihat kelas/mapel yang diajar (scoping).
- [ ] ATP: susun, urutkan, update progres.
- [ ] KKTP: indikator + interval 0-40/41-74/75-85/86-100; cetak.
- [ ] Materi: draft/published; link Drive/YouTube tervalidasi.
- [ ] Tugas: status draft/published/closed; nilai & feedback.
- [ ] Absensi: hadir/izin/sakit/alfa; rekap akurat.
- [ ] Penilaian: input nilai; data siap untuk laporan.
- [ ] AI Assistant: draft tidak auto-save ke DB kurikulum.

## Siswa
- [ ] Melihat materi published, tugas, dan nilai sendiri.
- [ ] Submit tugas (text/link/both); tidak bisa submit tugas closed.
- [ ] Melihat riwayat absensi sendiri.

## Laporan & Print
- [ ] Laporan ATP/KKTP/nilai/tugas/absensi cetak rapi (logo, header/footer, page-break).
- [ ] Filter kelas/mapel/tahun ajaran akurat.
