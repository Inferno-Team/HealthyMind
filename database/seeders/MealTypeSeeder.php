<?php

namespace Database\Seeders;

use App\Models\MealType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            "Breakfast", "Lunch", "Dinner", "Snacks", "Pre-Workout Meal", "Post-Workout Meal",
            "Healthy Meals", "Specialty Diets"
        ];
        foreach ($types as $type) {
            MealType::create(['name' => $type]);
        }
    }
}
