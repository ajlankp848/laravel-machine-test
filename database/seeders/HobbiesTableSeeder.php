<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HobbiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hobbies')->insert([
            ['hobby_name' => 'Reading'],
            ['hobby_name' => 'Traveling'],
            ['hobby_name' => 'Cooking'],
            ['hobby_name' => 'Photography'],
            ['hobby_name' => 'Gardening'],
            ['hobby_name' => 'Music'],
            ['hobby_name' => 'Painting'],
            ['hobby_name' => 'Writing'],
            ['hobby_name' => 'Fitness'],
            ['hobby_name' => 'Gaming'],
        ]);
    }
}
