# DATABASE_MAP — EduCP Manager / CPManager

Pemetaan skema basis data awal, kunci, indeks unik, serta relasi (Foreign Key). Tabel kurikulum ditandai khusus untuk fase mendatang.

## Tabel Saat Ini (Baseline - `database/schema.sql`)

### 1. `users`
- **Tanggung Jawab**: Menyimpan data akun pengguna sistem (administrator, guru, dan siswa).
- **Struktur**:
  | Kolom | Tipe Data | Null | Default | Atribut / Kunci | Catatan |
  | :--- | :--- | :--- | :--- | :--- | :--- |
  | `id` | `INT UNSIGNED` | NO | *Auto Increment* | **PRIMARY KEY** | ID unik pengguna |
  | `username` | `VARCHAR(60)` | NO | | **UNIQUE KEY (`uq_users_username`)** | Username login |
  | `password_hash`| `VARCHAR(255)`| NO | | | Password terenkripsi (bcrypt) |
  | `full_name` | `VARCHAR(120)`| NO | | | Nama lengkap user |
  | `role` | `ENUM` | NO | `'siswa'` | ENUM(`'admin'`, `'guru'`, `'siswa'`) | Level otorisasi |
  | `is_active` | `TINYINT(1)` | NO | `1` | | Status akun aktif (1) atau nonaktif (0) |
  | `created_at` | `DATETIME` | NO | `CURRENT_TIMESTAMP` | | Waktu pendaftaran akun |
- **Relasi (Foreign Keys)**: Tidak ada.

### 2. `school_profile`
- **Tanggung Jawab**: Menyimpan konfigurasi profil identitas sekolah (Fase 4).
- **Struktur**:
  | Kolom | Tipe Data | Null | Default | Atribut / Kunci | Catatan |
  | :--- | :--- | :--- | :--- | :--- | :--- |
  | `id` | `INT UNSIGNED` | NO | *Auto Increment* | **PRIMARY KEY** | ID profil sekolah |
  | `name` | `VARCHAR(150)`| NO | | | Nama sekolah |
  | `npsn` | `VARCHAR(20)` | YES| `NULL` | | Nomor Pokok Sekolah Nasional |
  | `address` | `VARCHAR(255)`| YES| `NULL` | | Alamat lengkap sekolah |
  | `headmaster` | `VARCHAR(120)`| YES| `NULL` | | Nama kepala sekolah |
  | `logo_path` | `VARCHAR(255)`| YES| `NULL` | | Path berkas logo sekolah |
- **Relasi (Foreign Keys)**: Tidak ada.

### 3. `academic_years`
- **Tanggung Jawab**: Menyimpan data tahun ajaran dan status semester aktif (Fase 4).
- **Struktur**:
  | Kolom | Tipe Data | Null | Default | Atribut / Kunci | Catatan |
  | :--- | :--- | :--- | :--- | :--- | :--- |
  | `id` | `INT UNSIGNED` | NO | *Auto Increment* | **PRIMARY KEY** | ID tahun ajaran |
  | `label` | `VARCHAR(20)` | NO | | **UNIQUE KEY (`uq_year_semester`)** [1] | Label tahun ajaran (mis: '2025/2026') |
  | `semester` | `ENUM` | NO | | **UNIQUE KEY (`uq_year_semester`)** [1] | Semester ganjil / genap |
  | `is_active` | `TINYINT(1)` | NO | `0` | | Status aktif (1) atau tidak aktif (0) |

  `[1]` Kombinasi kolom `(label, semester)` harus unik secara global.
- **Relasi (Foreign Keys)**: Tidak ada.

---

## Tabel Kurikulum (Direncanakan untuk Fase 7–10)

Sesuai komentar di `database/schema.sql`, tabel-tabel berikut **belum didefinisikan** dalam skema awal dan akan dibuat melalui berkas migrasi pada fase masing-masing:

| Nama Tabel | Rencana Kolom Utama | Rencana Relasi (FK) | Rencana Fase | Status Audit |
| :--- | :--- | :--- | :--- | :--- |
| `cp` | `id`, `code`, `title`, `description` | - | **Fase 7** | Belum Dibuat |
| `elemen_cp`| `id`, `cp_id`, `name`, `description` | `cp_id` &rarr; `cp.id` | **Fase 7** | Belum Dibuat |
| `tp` | `id`, `elemen_id`, `code`, `description` | `elemen_id` &rarr; `elemen_cp.id` | **Fase 8** | Belum Dibuat |
| `atp` | `id`, `tp_id`, `sequence_order` | `tp_id` &rarr; `tp.id` | **Fase 9** | Belum Dibuat |
| `kktp` | `id`, `tp_id`, `criteria`, `interval_value` | `tp_id` &rarr; `tp.id` | **Fase 10** | Belum Dibuat |

### Catatan Penting untuk Migrasi
- Semua nama tabel baru harus menggunakan huruf kecil dan snake_case.
- Setiap relasi foreign key harus menggunakan tipe data integer yang bersesuaian (`INT UNSIGNED`) untuk menghindari ketidakcocokan tipe index InnoDB.
- Gunakan constraint `ON DELETE RESTRICT` atau `ON DELETE CASCADE` dengan pertimbangan integritas data akademik yang matang (di bawah pengawasan Opus).

