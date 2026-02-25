<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    // Helper: Cek apakah sudah kadaluwarsa?
    public function isExpired(): bool
    {
        // Kadaluwarsa jika hari ini > tanggal selesai
        return $this->end_date < now()->format('Y-m-d');
    }

    // Scope: Filter hanya jadwal yang BENAR-BENAR aktif (Aktif checkbox + Belum expired)
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('end_date', '>=', now()->format('Y-m-d'));
    }
}
