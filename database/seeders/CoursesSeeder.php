<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Commons\Constants\CategoryValue;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiMoodles = DB::table('api_moodle')
            ->where('moodle_type', CategoryValue::MOODLE_TYPE_COURSE)
            ->pluck('id');

        foreach ($apiMoodles as $apiMoodleId) {
            DB::table('courses')->insert([
                'name' => 'Course for Moodle ID ' . $apiMoodleId,
                'api_moodle_id' => $apiMoodleId,
            ]);
        }
    }
}
