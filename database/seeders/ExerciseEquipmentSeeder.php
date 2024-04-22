<?php

namespace Database\Seeders;

use App\Models\ExerciseEquipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipments = [
            "Treadmill",
            "Elliptical trainer",
            "Stationary bike",
            "Rowing machine",
            "Stair climber/Stepmill",
            "Air bike",
            "Spin bike",
            "Dumbbells",
            "Barbells",
            "Weight plates",
            "Benches",
            "Power rack",
            "Smith machine",
            "Cable machine",
            "Leg press machine",
        ];

        foreach ($equipments as $equipment)
            ExerciseEquipment::create(['name' => $equipment]);
    }
}
