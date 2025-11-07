<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 10; $i++){
            Meja::create([
                'nomer_meja' => 'Meja ' . $i,
                'kursi' => $i % 2 == 0 ? '4' : '2',
                'status' => 'tersedia',
            ]);
        }
    }
}
