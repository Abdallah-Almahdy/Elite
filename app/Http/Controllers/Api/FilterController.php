<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\ApiTrait;

class FilterController extends Controller
{
    use ApiTrait;
     public function search(Request $request)
    {
        $useFilter = filter_var($request->input('useFilter', false), FILTER_VALIDATE_BOOLEAN);
        $minPrice  = $request->input('minPrice');
        $maxPrice  = $request->input('maxPrice');

        $query = Product::query();

        if ($useFilter) {
            if (!is_null($minPrice)) {
                $query->where('price', '>=', $minPrice);
            }
            if (!is_null($maxPrice)) {
                $query->where('price', '<=', $maxPrice);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($products->isEmpty()) {
            return $this->notFound('no results found');

        }

        return $this->success($products, 'request succeeded');

    }
}
