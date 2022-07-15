<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds to insert some fake data.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
            ->count(10)
            ->create();
    }
}
