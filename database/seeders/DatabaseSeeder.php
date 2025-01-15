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

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'email' => 'admin1@admin.com',
            'password' =>  Hash::make(123456),
        ]);
        // RubricTemplate::truncate();
        // EmsType::truncate();
        // ApiEms::truncate();
        // RubricScore::truncate();

        // $this->call([
        //     RubricTemplatesSeeder::class,
        //     EmsTypeSeeder::class,
        //     ApiEmsSeeder::class,
        //     // ApiMoodleSeeder::class,
        //     RubricScoreSeeder::class,
        //     CoursesSeeder::class
        // ]);
    }
}
