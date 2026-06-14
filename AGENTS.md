# AGENTS — EduCP Manager / CPManager

## Opus — Lead Architect & Security Reviewer
- Peran: keputusan arsitektur, SPEC fase kompleks, keamanan, AI policy, scope guard, reviewer final.
- Menangani fase: F2, F6, F9, F18, F20, F24, F25.
- Wajib mereview sebelum merge: perubahan arsitektur, keamanan, schema.sql/DB besar, AI, final hardening, release.
- Batasan: tidak melakukan rewrite; menjaga app tetap PHP Native MVC.

## Gemini — Context Auditor & Documentation/QA
- Peran: audit konteks besar, analisis alur, desain QA, dokumentasi, review prompt/knowledge base AI.
- Menangani fase: F0, F16, F17, F19, F21, F22.
- Area audit: ROUTE_MAP, DATABASE_MAP, alur import, laporan, knowledge base AI, skenario QA.
- Batasan: pada fase dokumen/QA tidak mengubah kode aplikasi.

## Codex — Implementation Engineer
- Peran: implementasi patch kode dari SPEC yang jelas.
- Menangani fase: F1, F3, F4, F5, F7, F8, F10, F11, F12, F13, F14, F15, F23.
- Lingkup file: routes (public/index.php), app/controllers, app/models, app/views, app/services.
- Batasan: tidak mengubah schema.sql tanpa persetujuan Opus; tidak menambah framework; tidak keluar scope.

## Aturan umum semua agent
- Baca CONVENTIONS.md & PROGRESS.md sebelum mulai.
- 1 fase = 1 branch = 1 agent; jangan loncat fase; jangan rewrite total.
- Semua POST CSRF; semua query PDO prepared statement; semua output di-escape; controller validasi role.
- Update PROGRESS.md di akhir setiap fase.
