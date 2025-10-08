<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Product;
use App\Traits\ApiTrait;
use App\Models\Favorit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\productsResource;

class FavoritesController extends Controller
{
    use ApiTrait;

    public function getUserFavorites(Request $request)
    {
        $userId = $request->user()->id;
        if (!$userId)
        {
            return $this->notFound('User ID is required');
        }

        $items = Favorit::with('product')
            ->where('user_id', $userId)
            ->get()
            ->pluck('product')
            ->filter();

        if ($items->isEmpty())
        {
            return response()->json([
                "data" => [],
                "message" => "no data",
                "code" => 200
        ],   200);
        }

        return $this->successCollection(productsResource::class, $items);
    }

    public function updateUserFavorites(Request $request)
    {

        $userId = $request->user()->id;
        $productId = $request->query('product_id');

        if (!$userId || !$productId) {
            return $this->notFound('User ID and Product ID are required');
        }

        $user = User::find($userId);
        $product = Product::find($productId);

        if (!$user || !$product) {
            return $this->notFound('User or Product not found');
        }


        $favoriteItem = Favorit::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();


        if (!$favoriteItem) {
            $item = Favorit::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return $this->success($item, 'Added successfully');
        }

        $favoriteItem->delete();
        return $this->success([], 'Deleted successfully');
    }


    public function  checkIsFavorite(Request $request)
    {

        $exists = Favorit::where([
            'user_id' => $request->user()->id,
            'product_id' => $request->query('product_id')
        ])->exists();

        if (!$exists) {
            return response()->json([
                'data' => 'false',
                'message' => 'false',
                'code' => '200'
            ], 200);
        }

         return response()->json([
                'data' => 'true',
                'message' => 'true',
                'code' => '200'
            ], 200);
        }
}
