<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds to insert some fake data.
     *
     * @return void
     */
    public function run()
    {
        Notification::truncate();

        $users = User::orderBy('id')
            ->take(10)
            ->pluck('id')
            ->toArray();

        foreach ($users as $user) {

            Notification::create([
                'user_id' => $user,
                'type' => array_rand(array_flip(Notification::$types))
            ]);

        }
    }
}
