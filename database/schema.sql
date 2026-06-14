-- database/schema.sql — EduCP Manager / CPManager
-- Skema awal minimal. schema.sql HANYA diubah pada fase DB yang disetujui Opus.
-- Perubahan lain lewat database/migrations/NNN_deskripsi.sql.

SET NAMES utf8mb4;
SET time_zone = '+07:00';

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username      VARCHAR(60)  NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name     VARCHAR(120) NOT NULL,
    role          ENUM('admin','guru','siswa') NOT NULL DEFAULT 'siswa',
    is_active     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS school_profile (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name          VARCHAR(150) NOT NULL,
    npsn          VARCHAR(20)  NULL,
    address       VARCHAR(255) NULL,
    headmaster    VARCHAR(120) NULL,
    logo_path     VARCHAR(255) NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academic_years (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    label       VARCHAR(20)  NOT NULL,    -- mis. 2025/2026
    semester    ENUM('ganjil','genap') NOT NULL,
    is_active   TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_year_semester (label, semester)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel kurikulum (cp, elemen_cp, tp, atp, kktp) ditambahkan pada Fase 7–10
-- melalui migrations yang disetujui Opus.
