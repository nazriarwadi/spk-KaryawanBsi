<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriterionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('criteria')->insert([
            ['code' => 'c1', 'name' => 'Capacity Plan', 'weight' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'c2', 'name' => 'Kedisiplinan', 'weight' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'c3', 'name' => 'Pengetahuan', 'weight' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'c4', 'name' => 'Loyalitas', 'weight' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'c5', 'name' => 'Team Work', 'weight' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
