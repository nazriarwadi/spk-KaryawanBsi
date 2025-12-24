<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentPolicy
{
    // Boleh melihat daftar (Menu Sidebar tampil)
    public function viewAny(User $user): bool
    {
        return true; // Semua role boleh masuk menu ini
    }

    // Boleh melihat detail (Tombol Mata / View) -> Ini syarat untuk bisa Cetak PDF
    public function view(User $user, Assessment $assessment): bool
    {
        return true; // Semua role boleh lihat detail
    }

    // Siapa yang boleh menambah data?
    public function create(User $user): bool
    {
        // Pimpinan TIDAK BOLEH, selain itu (Admin/HRD) BOLEH
        return !$user->isPimpinan();
    }

    // Siapa yang boleh mengedit?
    public function update(User $user, Assessment $assessment): bool
    {
        // Pimpinan TIDAK BOLEH
        return !$user->isPimpinan();
    }

    // Siapa yang boleh menghapus?
    public function delete(User $user, Assessment $assessment): bool
    {
        // Pimpinan TIDAK BOLEH
        return !$user->isPimpinan();
    }
}
