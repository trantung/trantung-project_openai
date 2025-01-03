<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ems_types')->insert([
            ['type_id' => rand(1, 10), 'type_name' => 'Ems Type 1'],
            ['type_id' => rand(11, 20), 'type_name' => 'Ems Type 2'],
            ['type_id' => rand(21, 30), 'type_name' => 'Ems Type 3'],
            ['type_id' => rand(30, 40), 'type_name' => 'Ems Type 4'],
        ]);
    }
}
