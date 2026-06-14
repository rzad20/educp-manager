-- database/seed.sql — data awal demo (JANGAN dipakai di production)
-- Password default di bawah adalah hash bcrypt untuk kata sandi: "password".

INSERT INTO users (username, password_hash, full_name, role) VALUES
  ('admin', '$2y$10$e0NRSE2bvqQz1u7yq5o8Iu0r6cJ0v9b3a3J8m2Fq6E2hT5gM5h8aC', 'Administrator', 'admin')
ON DUPLICATE KEY UPDATE username = username;

INSERT INTO school_profile (name, npsn, address, headmaster) VALUES
  ('Sekolah Contoh', '00000000', 'Jl. Pendidikan No. 1', 'Kepala Sekolah')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO academic_years (label, semester, is_active) VALUES
  ('2025/2026', 'ganjil', 1)
ON DUPLICATE KEY UPDATE label = label;
