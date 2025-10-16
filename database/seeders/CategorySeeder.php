<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name'=>'Pastries',
            'description'=>'pastries category.'
        ]);

        Category::create([
            'name'=>'Hot Drinks',
            'description'=>'Hot drinks and chocolate.'
        ]);

        Category::create([
            'name'=>'Beverages',
            'description'=>'Cold drink categories.'
        ]);

        Category::create([
            'name'=>'Food',
            'description'=>'Main course food.'
        ]);
    }
}
