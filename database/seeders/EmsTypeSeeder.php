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
        DB::table('ems_type')->insert([
            ['type_id' => rand(1, 10)],
            ['type_id' => rand(11, 20)],
            ['type_id' => rand(21, 30)],
        ]);
    }
}
