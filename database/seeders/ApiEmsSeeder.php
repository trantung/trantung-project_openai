<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiEmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emsTypeIds = DB::table('ems_type')->pluck('id');
        $rubricTemplateIds = DB::table('rubric_templates')->pluck('id');

        foreach (range(1, 10) as $index) {
            DB::table('api_es')->insert([
                'es_id' => rand(1, 100),
                'es_name' => 'EMS ' . $index,
                'es_type' => $emsTypeIds->random(),
                'rubric_template_id' => $rubricTemplateIds->random()
            ]);
        }
    }
}
