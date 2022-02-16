<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            'name' => 'mathematics',
            'category_id' => 1
        ]);

        DB::table('subjects')->insert([
            'name' => 'Geography',
            'category_id' => 2
        ]);

        DB::table('subjects')->insert([
            'name' => 'English',
            'category_id' => 3
        ]);
    }
}
