<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $categories = ([
            'Action',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Thriller'
        ]);

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
            ]);
        }
    }
}
