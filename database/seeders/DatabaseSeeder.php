<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\GoalPlanDisease;
use App\Models\QuantityType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $en_title = ['gram', 'kilogram', 'milliliter', 'liter', 'piece', 'slice', 'cup', 'teaspoon', 'tablespoon'];
        $ar_title = ['غرام', 'كيلوغرام', 'ميللي لتر', 'لتر', 'قطعة', 'شريحة', 'كوب', 'معلقة صغيرة', 'معلقة كبيرة'];
        $titles = [];
        for ($i = 0; $i < count($en_title); $i++) {
            $titles[] = [
                'en_title' => $en_title[$i],
                'ar_title' => $ar_title[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        QuantityType::insert($titles);
        Channel::create([
            'name' => 'all-chat',
            'type' => 'presence',
        ]);
        User::factory(1)->create([
            'first_name' => 'Healthy',
            'last_name' => 'Mind',
            'type' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin-password'),
            'email' => 'admin@mind.com',
            "status" => 'approved',
        ]);

        User::factory(10)->create();
        $this->call(GoalPlanDiseaseSeeder::class);
        $this->call([MealTypeSeeder::class]);
        $this->call([ExerciseTypeSeeder::class]);
        $this->call([ExerciseEquipmentSeeder::class]);
    }
}
