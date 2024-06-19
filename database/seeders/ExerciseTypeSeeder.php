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
            "Cardio",
            "Squat",
            "Aerobics",
            "Yoga",
            "Pilates",
            "Calisthenics",
            "Cable crossover",
            "Bent-over row",
            "Crunch",
            "Deadlift",
            "Leg",
            "Pull-up",
            "Forearm flexors",
        ];
        foreach ($types as $type) {
            ExerciseType::create(['name' => $type]);
        }
    }
}
