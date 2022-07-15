<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserProduct;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;

class UserProductSeeder extends Seeder
{
    /**
     * Run the database seeds to insert some fake data.
     *
     * @return void
     */
    public function run()
    {
        UserProduct::truncate();

        $users = User::orderBy('id')
            ->take(10)
            ->pluck('id')
            ->toArray();

        $products = Product::orderBy('id')
            ->take(10)
            ->pluck('id')
            ->toArray();

        foreach ($users as $user) {

            $productId = array_rand(array_flip($products));

            UserProduct::create([
                'user_id' => $user,
                'product_id' => $productId,
                'status' => array_rand(array_flip(UserProduct::$statuses))
            ]);

            Transaction::create([
                'user_id' => $user,
                'product_id' => $productId,
            ]);

        }
    }
}
