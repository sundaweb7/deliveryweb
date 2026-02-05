<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        Category::create(['name'=>'Makanan','slug'=>'makanan','status'=>'active']);
        Category::create(['name'=>'Minuman','slug'=>'minuman','status'=>'active']);
        Category::create(['name'=>'Lainnya','slug'=>'lainnya','status'=>'active']);
    }
}