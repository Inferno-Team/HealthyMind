<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Disease;
use App\Models\Goal;
use App\Models\GoalPlanDisease;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GoalPlanDiseaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();
        Goal::create(["name" => 'goal 1']);
        Goal::create(["name" => 'goal 2']);
        Goal::create(["name" => 'goal 3']);
        Goal::create(["name" => 'goal 4']);
        Plan::create(["name" => "Plan 1"]);
        Plan::create(["name" => "Plan 2"]);
        Plan::create(["name" => "Plan 3"]);
        Plan::create(["name" => "Plan 4"]);
        Disease::create(["name" => "Disease 1"]);
        Disease::create(["name" => "Disease 2"]);
        Disease::create(["name" => "Disease 3"]);
        Disease::create(["name" => "Disease 4"]);
        for ($i = 1; $i <= 4; $i++) {
            $goalPlanDisease = new GoalPlanDisease;
            $goalPlanDisease->plan_id = $i;
            $goalPlanDisease->goal_ids = '';
            $goalPlanDisease->disease_ids = '';
            for ($j = 1; $j <= 4; $j++) {
                $goalPlanDisease->goal_ids .= "$j";
                $goalPlanDisease->disease_ids .= "$j";
                if ($j != 4) {
                    $goalPlanDisease->goal_ids .= ",";
                    $goalPlanDisease->disease_ids .= ",";
                }
            }
            $goalPlanDisease->save();
        }
        DB::commit();
    }
}
