<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AchievementEmployee;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $targetEmployee = Employee::all()[0];
        for ($i = 0; $i < 3; $i++) {
            AchievementEmployee::create([
                'employee_id' => $targetEmployee->id,
                'achievement_id' => Achievement::all()->random()->id,
                'achievement_date' => now(),
            ]);
        }

        $targetAchievement = Achievement::all()[0];
        for ($i = 0; $i < 3; $i++) {
            AchievementEmployee::create([
                'achievement_id' => $targetAchievement->id,
                'employee_id' => Employee::all()->random()->id,
                'achievement_date' => now(),
            ]);
        }
    }
}
