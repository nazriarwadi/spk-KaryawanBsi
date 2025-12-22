<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Mengizinkan kolom ini untuk diisi data
    protected $fillable = [
        'nip',
        'name',
    ];

    // Relasi ke tabel assessments (Satu karyawan punya banyak penilaian)
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
