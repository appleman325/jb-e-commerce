<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new user
     * POST /users
     *
     * @return App\Models\User
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users',
        ]);

        $user = User::create($request->all());

        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user
        ]);
    }

    /**
     * Get the required user based on ID
     * GET /users/{id}
     *
     * @return App\Models\User
     */
    public function show($id)
    {
        $user = User::with('products')
            ->with('notifications')
            ->find($id);

        if (is_null($user)) {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }

        return response()->json([
            'message' => 'User retrieved successfully!',
            'user' => $user
        ]);
    }


}
