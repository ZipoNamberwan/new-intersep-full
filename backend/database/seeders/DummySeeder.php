<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 200; $i++) {
            Company::create([
                'name' => fake()->company(),
                'id_sbr' => fake()->numberBetween(1000, 9999),
                'kab' => 13,
                'kab_name' => 'Probolinggo',
                'kec' => 010,
                'kec_name' => 'Sukapura',
                'des' => 001,
                'des_name' => 'Sukapura',
                'address' => fake()->address(),
            ]);
        }
    }
}
