<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'employee_id', 
        'schedule_id',
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

    // --- LOGIKA PERHITUNGAN OTOMATIS (VERTIKAL / GLOBAL) ---
    protected static function booted()
    {
        // Jalankan perhitungan setiap kali data disimpan (Created/Updated)
        static::saved(function ($model) {
            // Gunakan withoutEvents agar tidak looping infinite
            static::withoutEvents(function () use ($model) {
                self::recalculateAll($model->schedule_id);
            });
        });

        // Jalankan perhitungan setiap kali data dihapus
        static::deleted(function ($model) {
            static::withoutEvents(function () use ($model) {
                self::recalculateAll($model->schedule_id);
            });
        });
    }

    /**
     * Fungsi Hitung SMART (Metode Vertikal Sesuai Tabel 3.4 & 3.5 Skripsi)
     * Menggunakan Rumus BENEFIT: (Input - Min) / (Max - Min)
     */
    public static function recalculateAll($scheduleId)
    {
        // 1. Ambil Semua Data di Jadwal Tersebut
        $assessments = self::where('schedule_id', $scheduleId)->get();
        
        if ($assessments->isEmpty()) return;

        // 2. Cari Nilai MAX dan MIN Global untuk setiap kriteria (Vertikal)
        $stats = [
            'c1' => ['max' => $assessments->max('c1_capacity_plan'), 'min' => $assessments->min('c1_capacity_plan')],
            'c2' => ['max' => $assessments->max('c2_kedisiplinan'), 'min' => $assessments->min('c2_kedisiplinan')],
            'c3' => ['max' => $assessments->max('c3_pengetahuan'), 'min' => $assessments->min('c3_pengetahuan')],
            'c4' => ['max' => $assessments->max('c4_loyalitas'), 'min' => $assessments->min('c4_loyalitas')],
            'c5' => ['max' => $assessments->max('c5_team_work'), 'min' => $assessments->min('c5_team_work')],
        ];

        // 3. Bobot Sesuai Tabel 3.1 [cite: 963]
        $weights = [
            'c1' => 0.70, 
            'c2' => 0.10, 
            'c3' => 0.10, 
            'c4' => 0.05, 
            'c5' => 0.05
        ];

        // 4. Loop Hitung Ulang Setiap Karyawan
        foreach ($assessments as $data) {
            $totalScore = 0;

            foreach ($weights as $key => $weight) {
                // Tentukan nama kolom database
                $column = $key . ($key == 'c1' ? '_capacity_plan' : 
                                 ($key == 'c2' ? '_kedisiplinan' : 
                                 ($key == 'c3' ? '_pengetahuan' : 
                                 ($key == 'c4' ? '_loyalitas' : '_team_work'))));
                
                $input = $data->{$column};
                $max = $stats[$key]['max'];
                $min = $stats[$key]['min'];

                // --- RUMUS BENEFIT (Sesuai Contoh Hitungan Bab 3 Halaman 27) ---
                // [cite: 975] Contoh Ilham C1: (87 - 82) / (90 - 82) * 100 = 62.5
                // Rumus: 100 * (Input - Min) / (Max - Min)
                
                if ($max - $min == 0) {
                    // Jika semua nilai sama (Max = Min), nilai utility dianggap 100 atau 0
                    // Biasanya 100 jika nilainya tinggi, tapi 0 lebih aman untuk menghindari bias
                    $utility = 0; 
                } else {
                    $utility = 100 * ($input - $min) / ($max - $min);
                }

                // Tambahkan ke Total Skor
                $totalScore += $utility * $weight;
            }

            // Simpan Nilai Akhir ke Database
            self::where('id', $data->id)->update(['final_score' => $totalScore]);
        }
    }
}