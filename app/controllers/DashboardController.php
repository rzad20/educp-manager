<?php
declare(strict_types=1);

/** Controller contoh. Setiap controller WAJIB validasi role di awal. */
class DashboardController
{
    public function index(): void
    {
        Auth::require('admin', 'guru', 'siswa');
        view('dashboard', ['title' => 'Dashboard']);
    }
}
