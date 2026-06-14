# ROUTE_MAP — EduCP Manager / CPManager

Daftar pemetaan rute aplikasi dari parameter `?page=...` ke Controller, Method, View, dan batasan Hak Akses (Role).

## Rute Terdaftar (Aktif di `public/index.php`)

| `page` | Controller | Method | View | Hak Akses (Role) | Catatan Keamanan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `login` | `AuthController` | `showLogin` | `auth/login` | Public (Semua) | Form login dengan CSRF token; auto-redirect jika sudah login |
| `login_submit` | `AuthController` | `login` | - | Public (Semua) | **POST**. CSRF wajib divalidasi di middleware. Rate limited (5x/15min). Session regenerate on success. |
| `logout` | `AuthController` | `logout` | - | Semua (Telah login) | **POST**. CSRF wajib. Hancurkan session + clear cookie |
| `dashboard` | `DashboardController` | `index` | `dashboard` | `admin`, `guru`, `siswa` | `Auth::require('admin', 'guru', 'siswa')` |
| `teacher_material_update` | `TeacherController` | `materialUpdate` | `guru/material_update` | `guru` | `Auth::require('guru')`. Form dan persistensi dilanjutkan pada Fase 11 |
| `teacher_assignment_update` | `TeacherController` | `assignmentUpdate` | `guru/assignment_update` | `guru` | `Auth::require('guru')`. Form dan persistensi dilanjutkan pada Fase 12 |

## Matriks Akses Role

| Route | `admin` | `guru` | `siswa` | HTTP jika Violation |
|:------|:-------:|:------:|:-------:|:-------------------|
| `login` | ✅ | ✅ | ✅ | 200 (redirect jika sudah login) |
| `login_submit` | ✅ | ✅ | ✅ | 200 (redirect on success) |
| `logout` | ✅ | ✅ | ✅ | 200 |
| `dashboard` | ✅ | ✅ | ✅ | 403 |
| `teacher_material_update` | ❌ | ✅ | ❌ | 403 |
| `teacher_assignment_update` | ❌ | ✅ | ❌ | 403 |

✅ = Diperbolehkan | ❌ = Ditolak (HTTP 403)

## Rute yang Distabilkan pada Fase 1

- `teacher_material_update` dan `teacher_assignment_update` telah terhubung ke controller, method, dan view guru.
- Kedua halaman memakai guard role `guru` via `Auth::require('guru')`.
- Implementasi form dan persistensi data tetap berada pada Fase 11 dan Fase 12.

## Catatan Keamanan (Fase 2)

### Session Hardening
- Cookie: `HttpOnly=true`, `SameSite=Strict`, `Secure=true` (production only)
- Session ID diregenerate setiap 15 menit
- Session ID diregenerate saat login berhasil (session fixation prevention)

### CSRF Protection
- **Semua POST** wajib memiliki token CSRF
- Token di-generate via `Csrf::field()` di form
- Validasi di middleware `public/index.php`
- Token di-regenerate setelah validasi berhasil

### Rate Limiting
- Login: maks 5 percobaan per IP dalam 15 menit
- Setelah lockout, tampilkan pesan umum (tidak disclose username validity)

### Audit Log
- Failed login attempts di-log dengan IP dan timestamp
- CSRF validation failures di-log tanpa expose detail ke user

## Rute Masa Depan (Fase 3+)

| `page` (Planned) | Controller | Hak Akses | Fase |
|:---|:---|:---|:---|
| `profile` | ProfileController | semua role | F3 |
| `users` | UserController | `admin` | F5 |
| `classes` | ClassController | `admin` | F5 |
| `subjects` | SubjectController | `admin` | F5 |
| `cp_list` | CurriculumController | `admin`, `guru` | F7 |
| `materials` | MaterialController | `admin`, `guru` | F11 |
| `assignments` | AssignmentController | `admin`, `guru`, `siswa` | F12 |
| `attendance` | AttendanceController | `admin`, `guru` | F13 |
| `assessments` | AssessmentController | `admin`, `guru` | F14 |