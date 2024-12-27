<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiMoodleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubricTemplateIds = DB::table('rubric_templates')->pluck('id');

        foreach (range(1, 10) as $index) {
            DB::table('api_moodle')->insert([
                'moodle_id' => rand(1, 100),
                'moodle_name' => 'Moodle Name ' . $index,
                'moodle_type' => ['Type A', 'Type B', 'Type C'][array_rand(['Type A', 'Type B', 'Type C'])],
                'created_at' => now(),
                'updated_at' => now(),
                'rubric_template_id' => $rubricTemplateIds->random()
            ]);
        }
    }
}
