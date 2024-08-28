<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Subsector;
use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 0; $i < 200; $i++) {
        //     Company::create([
        //         'name' => fake()->company(),
        //         'id_sbr' => fake()->numberBetween(1000, 9999),
        //         'kab' => fake()->numberBetween(1, 38),
        //         'kec' => fake()->numberBetween(1, 600),
        //         'des' => fake()->numberBetween(1, 600),
        //         'bs' => fake()->numberBetween(1, 600),
        //         'address' => fake()->address(),
        //     ]);
        // }

        Subsector::create(['name' => 'Tanaman Pangan']);
        Subsector::create(['name' => 'Hortikultura']);
        Subsector::create(['name' => 'Tanaman Pangan']);


        Survey::create(['name' => 'Ubinan']);
        Survey::create(['name' => 'KSA']);
        Survey::create(['name' => 'VHTS']);
    }
}
