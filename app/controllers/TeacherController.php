<?php
declare(strict_types=1);

/** Penyambung route modul guru. Implementasi data dilanjutkan pada fase modul terkait. */
class TeacherController
{
    public function materialUpdate(): void
    {
        Auth::require('guru');
        view('guru/material_update', ['title' => 'Perbarui Materi']);
    }

    public function assignmentUpdate(): void
    {
        Auth::require('guru');
        view('guru/assignment_update', ['title' => 'Perbarui Tugas']);
    }
}
