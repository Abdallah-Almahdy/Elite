<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\productsResource;
use App\Http\Resources\sectionsAndproductsResource;
//use App\Http\Resources\ProductPaginationResource;
// use App\Http\Resources\productCollection;
use App\Models\Product;
use App\Models\SubSection;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;

class ProductsController extends Controller
{
    use ApiTrait;


    public function GetAllProducts()
    {


        return  sectionsAndproductsResource::collection(SubSection::get());
    }

    public function get_product($id)
    {
        $data = Product::find($id);


        return $this->success($data);
    }



    public function product_info(Request $request)
    {

        // $prod_id = product::find();


        $data = Product::find($request->query('prod_id'));
        if (!$data) {
            return [];
        }
        return new productsResource($data);
    }


    public function get_all_products(Request $request)
    {
        // $data = Product::where('active', 1)->get();

        // return  productsResource::collection($data);

        $perPage = $request->input('per_page', 10);

        $data = Product::where('active', 1)->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'total_products' => $data->total(),
            'total_pages'    => $data->lastPage(),
            'current_page'   => $data->currentPage(),
            'per_page'       => $data->perPage(),
            'data'           => productsResource::collection($data->items()),
        ]);
    }



    public function create_product(Request $request)
    {
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'photo' => $request->photo->storeAs('products', rand() . '.jpg', 'my_public'),
            'section_id' => $request->section,
            'active' => $request->stock ?? true,
            'qnt' => $request->stockQnt,
        ];

        Product::create($data);

        return  $this->success("product created successfully");
    }




    public function get_best_sellers()
    {
        $data =  Product::where('purchase_count', '>', 0)->orderBy('purchase_count', 'desc')->get();

        return  productsResource::collection($data);
    }




    public function get_offer_rate()
    {
        $data =  Product::where('offer_rate', '>', 0)->orderBy('offer_rate', 'desc')->get();

        if (!isset($data[0])) {
            return  [];
        } else {

            return  productsResource::collection($data);
        }
    }





    //     public function products_search(Request $request)
    // {
    //     $useFilter   = filter_var($request->input('useFilter', false), FILTER_VALIDATE_BOOLEAN);
    //     $minPrice    = $request->input('minPrice');
    //     $maxPrice    = $request->input('maxPrice');
    //     $searchType  = $request->query('type');
    //     $searchValue = $request->query('search_value');

    //     $query = Product::query();


    //     if ($searchValue) {
    //         if ($searchType == '1') {
    //             $query->where('bar_code', 'LIKE', "%{$searchValue}%");
    //         } elseif ($searchType == '2') {
    //             $query->where('name', 'LIKE', "%{$searchValue}%");
    //         }
    //     }


    //     if ($useFilter) {
    //         if (!is_null($minPrice)) {
    //             $query->where('price', '>=', $minPrice);
    //         }
    //         if (!is_null($maxPrice)) {
    //             $query->where('price', '<=', $maxPrice);
    //         }
    //     }

    //     $products = $query->orderBy('created_at', 'desc')->paginate(10);

    //     if ($products->count() === 0) {
    //         return [];
    //     }

    //     return productsResource::collection($products);
    // }

    public function products_search(Request $request)
    {
        $useFilter   = filter_var($request->input('useFilter', false), FILTER_VALIDATE_BOOLEAN);
        $minPrice    = $request->input('minPrice');
        $maxPrice    = $request->input('maxPrice');
        $searchType  = $request->query('type');
        $searchValue = $request->query('search_value');

        $query = Product::query();


        if ($searchValue) {
            if ($searchType == '1') {
                $query->where('bar_code', 'LIKE', "%{$searchValue}%");
            } elseif ($searchType == '2') {
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }
        }


        if ($useFilter) {
            if (!is_null($minPrice)) {
                $query->where(function ($q) use ($minPrice) {
                    $q->where('price', '>=', $minPrice)
                        ->orWhereRaw('(price - (price * offer_rate / 100)) >= ?', [$minPrice]);
                });
            }
            if (!is_null($maxPrice)) {
                $query->where(function ($q) use ($maxPrice) {
                    $q->where('price', '<=', $maxPrice)
                        ->orWhereRaw('(price - (price * offer_rate / 100)) <= ?', [$maxPrice]);
                });
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($products->count() === 0) {
            return [];
        }

        return productsResource::collection($products);
    }




    public function get_all_products_pagination(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $data = Product::where('active', 1)->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'total_products' => $data->total(),
            'total_pages'    => $data->lastPage(),
            'current_page'   => $data->currentPage(),
            'per_page'       => $data->perPage(),
            'data'           => productsResource::collection($data->items()),
        ]);
    }
}
