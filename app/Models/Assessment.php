<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'employee_id', 'schedule_id', // Tambah schedule_id
        'c1_capacity_plan', 'c2_kedisiplinan',
        'c3_pengetahuan', 'c4_loyalitas', 'c5_team_work',
        'final_score'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    protected static function booted()
    {
        static::saving(function ($assessment) {
            // AMBIL BOBOT DARI DATABASE (Fitur Dinamis)
            // Jika data kriteria tidak ditemukan, pakai default proposal
            $w1 = Criterion::where('code', 'c1')->value('weight') ?? 70;
            $w2 = Criterion::where('code', 'c2')->value('weight') ?? 10;
            $w3 = Criterion::where('code', 'c3')->value('weight') ?? 10;
            $w4 = Criterion::where('code', 'c4')->value('weight') ?? 5;
            $w5 = Criterion::where('code', 'c5')->value('weight') ?? 5;

            // Normalisasi bobot (dibagi 100 agar jadi desimal, misal 70 jadi 0.7)
            // Rumus SMART: Nilai * Bobot Normalisasi
            $assessment->final_score =
                ($assessment->c1_capacity_plan * ($w1 / 100)) +
                ($assessment->c2_kedisiplinan * ($w2 / 100)) +
                ($assessment->c3_pengetahuan * ($w3 / 100)) +
                ($assessment->c4_loyalitas * ($w4 / 100)) +
                ($assessment->c5_team_work * ($w5 / 100));
        });
    }
}
