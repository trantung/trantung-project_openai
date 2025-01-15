<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\RubricTemplate;
use App\Models\EmsType;
use App\Models\RubricScore;
use App\Models\ApiEms;
use App\Models\Roles;
use App\Models\UserRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Điều kiện kiểm tra
            [
                'name' => 'admin',
                'password' => Hash::make(123456),
            ]
        );
        $user_id = $user->id;
        
        // Kiểm tra và tạo vai trò
        $role = Roles::firstOrCreate(
            ['short_name' => 'admin'], // Điều kiện kiểm tra
            ['name' => 'admin']
        );
        $role_id = $role->id;
        
        // Kiểm tra và liên kết vai trò với người dùng
        UserRole::firstOrCreate([
            'role_id' => $role_id,
            'user_id' => $user_id,
        ]);
        // RubricTemplate::truncate();
        // EmsType::truncate();
        // ApiEms::truncate();
        // RubricScore::truncate();

        $this->call([
            RubricTemplatesSeeder::class,
            EmsTypeSeeder::class,
            ApiEmsSeeder::class,
            // ApiMoodleSeeder::class,
            RubricScoreSeeder::class,
            CoursesSeeder::class
        ]);
    }
}
