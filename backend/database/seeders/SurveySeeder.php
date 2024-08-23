<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Survey::create(['name' => 'Survey 1']);
        Survey::create(['name' => 'Survey 2']);
        Survey::create(['name' => 'Survey 3']);
    }
}
