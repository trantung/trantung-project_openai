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
        $emsTypeIds = DB::table('ems_types')->pluck('id');
        $rubricTemplateIds = DB::table('rubric_templates')->pluck('id');

        foreach (range(1, 10) as $index) {
            DB::table('api_ems')->insert([
                'ems_id' => rand(1, 100),
                'ems_name' => 'EMS ' . $index,
                'ems_type_id' => $emsTypeIds->random(),
                'rubric_template_id' => $rubricTemplateIds->random()
            ]);
        }
    }
}
