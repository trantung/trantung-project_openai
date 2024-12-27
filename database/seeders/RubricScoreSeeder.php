<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubricScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubricTemplateIds = DB::table('rubric_templates')->pluck('id');

        foreach (range(1, 20) as $index) {
            DB::table('rubric_scores')->insert([
                'rubric_template_id' => $rubricTemplateIds->random(),
                'lms_score' => rand(1, 100),
                'rule_score' => rand(1, 100),
            ]);
        }
    }
}
