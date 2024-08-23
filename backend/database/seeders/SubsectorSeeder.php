<?php

namespace Database\Seeders;

use App\Models\Subsector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubsectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subsector::create(['name' => 'Tanaman Pangan']);
        Subsector::create(['name' => 'Hortikultura']);
        Subsector::create(['name' => 'Perkebunan']);
    }
}
