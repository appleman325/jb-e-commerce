<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\UserProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Get all products no matter status
     * GET /products
     *
     * @return App\Models\Product
     */
    public function getProducts()
    {
        // NOTE: Permission to access this API endpoint can be implemented.
        // For example, maybe only admins or certain users can see all products in admin panel.
        // The logic can be either added here or through a customized middleware.
        $products = Product::paginate(10);

        return response()->json([
            'message' => 'Products retrieved successfully!',
            'products' => $products->toArray()
        ]);
    }

    /**
     * Get all active products
     * GET /active_products
     *
     * @return App\Models\Product
     */
    public function getActiveProducts()
    {
        $activeProducts = Product::where('status', Product::ACTIVE)->paginate(10);

        return response()->json([
            'message' => 'Active products retrieved successfully!',
            'products' => $activeProducts
        ]);
    }

    /**
     * Update an existing product's status or monthly inventory
     * POST /products/{id}
     *
     * @return App\Models\Product
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'status' => Rule::in(Product::$statuses),
            'monthly_inventory' => 'numeric|gte:0'
        ]);

        if (is_null($request->monthly_inventory) && is_null($request->status)) {
            return response()->json([
                'message' => 'Did not find values to update the project.'
            ], 404);
        }

        $product = Product::find($id);

        if ($product) {

            if (isset($request->monthly_inventory) && $request->monthly_inventory == 0) {
                // Update product's status to on-hold if its monthly inventory is 0
                $product->update([
                    'monthly_inventory' => 0,
                    'status' => Product::ON_HOLD
                ]);

                // Notify users that are currently approved on a product
                // that there is no more inventory available
                $this->notifyUser($id, Notification::DEPLETED);

            } elseif (isset($request->monthly_inventory) && is_null($request->status)) {
                // Product's monthly inventory (greater than 0) got updated
                if ($product->status === Product::ON_HOLD) {
                    // Notify users when a product status changes (from ON_HOLD to ACTIVE).
                    //For example when an available product expires
                    $this->notifyUser($id, Notification::STATUS_CHANGE);
                }

                $product->update([
                    'monthly_inventory' => $request->monthly_inventory ?? $product->monthly_inventory,
                    'status' => $product->status === Product::ON_HOLD ? Product::ACTIVE : $product->status
                ]);

            } elseif (isset($request->status) && $request->status !== $product->status) {
                // Product's status got updated.
                // Monthly inventory may or may not got updated.
                $product->update([
                    'monthly_inventory' => $request->monthly_inventory ?? $product->monthly_inventory,
                    'status' => $product->status
                ]);

                // Notify users when a product status changes.
                // For example when an available product expires
                $this->notifyUser($id, Notification::STATUS_CHANGE);

            }

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);

        }

        return response()->json([
            'message' => 'Product not found!'
        ], 404);
    }

    // Send user notification due to product status change
    private function notifyUser($productId, $notificationType)
    {
        // Notify users when a product status changes.
        $notifyUsers = UserProduct::where('product_id', $productId)
            ->pluck('user_id')
            ->toArray();

        foreach ($notifyUsers as $notifyUser) {

            Notification::updateOrCreate([
                'user_id' => $notifyUser,
                'read_at' => NULL,
                'type' => $notificationType
            ], [
                'data' => json_encode(['product_id' => $productId]),
            ]);

        }
    }
}
