<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutoringSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('tutorings')->insert([
            'tutor_id' => rand(1, 10),
            'subject_id' => rand(1, 3),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHour(1)->format('Y-m-d H:i:s'),
            'hourly_price' => 60000,
            'hourly_duration' => 1,
            'title' => 'learn something with me 1',
            'description' => 'you can do something',
        ]);

        DB::table('tutorings')->insert([
            'tutor_id' => rand(1, 10),
            'subject_id' => rand(1, 3),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHour(1)->format('Y-m-d H:i:s'),
            'hourly_price' => 60000,
            'hourly_duration' => 2,
            'title' => 'learn something with me 2',
            'description' => 'you can do something',
        ]);

        DB::table('tutorings')->insert([
            'tutor_id' => rand(1, 10),
            'subject_id' => rand(1, 3),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHour(1)->format('Y-m-d H:i:s'),
            'hourly_price' => 60000,
            'hourly_duration' => 1,
            'title' => 'learn something with me 3',
            'description' => 'you can do something',
        ]);
    }
}
