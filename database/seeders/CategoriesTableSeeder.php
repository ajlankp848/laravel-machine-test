<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['category_name' => 'Technology'],
            ['category_name' => 'Health'],
            ['category_name' => 'Education'],
            ['category_name' => 'Entertainment'],
            ['category_name' => 'Sports'],
            ['category_name' => 'Finance'],
            ['category_name' => 'Travel'],
            ['category_name' => 'Food'],
            ['category_name' => 'Fashion'],
            ['category_name' => 'Lifestyle'],
        ]);
    }
}
