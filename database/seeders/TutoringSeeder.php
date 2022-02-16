<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tutorings')->insert([
            'tutor_id'=>rand(1,10),
            'subject_id' => rand(1,3),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHour(1)->format('Y-m-d H:i:s'),
            'hourly_price' => 60000
        ]);

        DB::table('tutorings')->insert([
            'tutor_id'=>rand(1,10),
            'subject_id' => rand(1,3),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHour(1)->format('Y-m-d H:i:s'),
            'hourly_price' => 60000
        ]);

        DB::table('tutorings')->insert([
            'tutor_id'=>rand(1,10),
            'subject_id' => rand(1,3),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHour(1)->format('Y-m-d H:i:s'),
            'hourly_price' => 60000
        ]);
    }
}
