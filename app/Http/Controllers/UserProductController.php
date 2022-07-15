<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\UserProduct;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserProductController extends Controller
{
    /**
     * Create an user application
     * POST /users/{user_id}/products
     *
     * @return App\Models\UserProduct
     */
    public function create($user_id, Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric|gt:0'
        ]);

        // NOTE: In real world we may need to add columns like
        // valid_from, valid_end in the users_products table, since
        // a user could have multiple applications on the same product
        // but in different time window.
        // I didn't implement this logic here since it is out of scope.
        // That's why I'm using updateOrCreate here to ensure there are
        // no duplications in this table.
        $userProduct = UserProduct::updateOrCreate([
            'user_id' => $user_id,
            'product_id' => $request->product_id
        ], [
            'status' => UserProduct::PENDING
        ]);

        if ($userProduct) {
            // Create a new record in transaction table
            Transaction::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id
            ]);

            return response()->json([
                'message' => 'User application created successfully!',
                'userProduct' => $userProduct
            ]);

        }

        return response()->json([
            'message' => 'Cannot create an user application!'
        ], 500);

    }

    /**
     * Update an user application's status
     * POST /users/{user_id}/products/{product_id}
     *
     * @return App\Models\UserProduct
     */
    public function update($user_id, $product_id, Request $request)
    {
        $this->validate($request, [
            'status' => 'required|' . Rule::in(UserProduct::$statuses)
        ]);

        // NOTE: In real world we may need to add columns like
        // valid_from, valid_end in the users_products table, since
        // a user could have multiple applications on the same product
        // but in different time window.
        // I didn't implement this logic here since it is out of scope,
        // but I want mention it here.
        $userProduct = UserProduct::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($userProduct) {

            $userProduct = UserProduct::where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->update([
                    'status' => $request->status
                ]);

            Notification::updateOrCreate([
                'user_id' => $user_id,
                'read_at' => NULL,
                'type' => Notification::APPROVED
            ], [
                'data' => json_encode([
                    'product_id' => $product_id,
                    'application_status' => $request->status
                ]),
            ]);

            return response()->json([
                'message' => 'User application updated successfully!',
                'userProduct' => $userProduct
            ]);

        }

        return response()->json([
            'message' => 'User application not found!'
        ], 404);
    }
}
