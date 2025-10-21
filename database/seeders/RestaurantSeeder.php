<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::create([
            'name'=>'Art Cafe',
            'address'=>'302 Wabera str',
            'description'=>'Art Cafe'
        ]);

        Restaurant::create([
            'name'=>'Fork N Flame',
            'address'=>'20 Madaraka',
            'description'=>'Fork N Flame'
        ]);

        Restaurant::create([
            'name'=>'Kilimanjaro',
            'address'=>'3 Moi ave',
            'description'=>'Kilimanjaro'
        ]);

        Restaurant::create([
            'name'=>'Java House',
            'address'=>'23 Imara Daima',
            'description'=>'Java House'
        ]);

        Restaurant::create([
            'name'=>'Charlies',
            'address'=>'34 Nyayo',
            'description'=>'Charlies'
        ]);
    }
}
