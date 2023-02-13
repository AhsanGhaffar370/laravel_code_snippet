<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Category;
use Carbon\Carbon;

class CreateCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            [ // 1
                'name' => 'Health & Medical', 
                'slug' => 'health-medical', 
                'image' => 'img1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [ // 2
                'name' => 'Accident & Emergancy', 
                'slug' => 'accident-emergency', 
                'image' => 'img2.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [ // 3
                'name' => 'Pet & Animal', 
                'slug' => 'pet-animal', 
                'image' => 'img3.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [ // 4
                'name' => 'Cancer', 
                'slug' => 'cancer', 
                'image' => 'img4.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [ // 5
                'name' => 'In Memory', 
                'slug' => 'in-memory', 
                'image' => 'img5.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [ // 6
                'name' => 'Bucket List', 
                'slug' => 'bucket-list', 
                'image' => 'img6.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
