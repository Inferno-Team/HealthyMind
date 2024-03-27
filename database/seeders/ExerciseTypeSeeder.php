<?php

namespace Database\Seeders;

use App\Models\ExerciseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            "Aerobic",
            "Strength Training",
            "Flexibility",
            "Balance and Stability",
            "High-Intensity Interval",
            "Cross-Training",
            "Endurance Training",
            "Functional Training",
            "Rehabilitation Exercises",
            "Mind-Body Exercises",
        ];
        foreach($types as $type){
            ExerciseType::create(['name' =>$type]);
        }
    }
}
