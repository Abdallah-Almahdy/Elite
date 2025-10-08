<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\productsResource;
use App\Http\Resources\sectionsResource;
use App\Models\Product;
use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    public function get_category($id)
    {
        $data = Section::find($id);

        // if (!$data) {
        //     return response()->json(['message' => 'not found fdf'], 404);
        // }

        return response()->json($data);
    }
    public function get_all_categories()
    {
        $data = Section::all();



        return  sectionsResource::collection($data);
    }
    public function get_all_category_products(Request $request)
    {
        $data = Product::where('section_id', $request->query('cat_id'))->get();



        if (!isset($data[0])) {
            return  response()->json([
                'code' => 404,
                'message' => 'لا يوجد منتجات لهذا القسم'
            ], 404);
        } else {
            $cat_name  = SubSection::find($request->query('cat_id'))->name;
            return  [
                'category_name' => $cat_name,
                'products' => productsResource::collection($data)
            ];
        }
    }

    // public function get_sub_of_cat(Request $request)
    // {
    //     $main_section = section::find($request->query('main_sec_id'));;
    //     $products = [];
    //     $sub_sections = SubSection::where('main_section_id', $main_section->id)->get();


    //     foreach ($sub_sections as $sub_section) {
    //         if (isset($sub_section->id) ?? null) {
    //             $section_products  = product::where('section_id', $sub_section->id)->get();
    //             if (count($section_products) > 0) {
    //                 foreach ($section_products as $product) {
    //                     array_push($products, $product);
    //                 }
    //             }
    //         }
    //     }

    //     if (!isset($main_section->id) || count($sub_sections) == 0 || count($products) == 0) {
    //         return  response()->json([
    //             'code' => 404,
    //             'message' => 'not found '
    //         ], 404);
    //     } else {

    //         return  response()->json([
    //             'data' => [
    //                 'sub_sections' => sectionsResource::collection($sub_sections),
    //                 'products' =>  productsResource::collection($products)
    //             ],
    //             'code' => 200,
    //             'message' => 'found successfully  '
    //         ], 200);
    //     }
    // }
    public function get_sub_of_cat(Request $request)
    {
        // Fetch the main section based on the provided main_sec_id from the query string
        $main_section = Section::find($request->query('main_sec_id'));

        // If the main section doesn't exist, return a 404 response
        if (!$main_section) {
            return response()->json([
                'code' => 404,
                'message' => 'not found ',
            ], 404);
        }

        // Get all sub-sections for the given main_section_id
        $sub_sections = SubSection::where('main_section_id', $main_section->id)->get();

        // If there are no sub-sections, return a 404 response
        if ($sub_sections->isEmpty()) {
            return response()->json([
                'code' => 404,
                'message' => 'لا يوجد اقسام فرعية لهذا القسم',
            ], 404);
        }

        // Initialize an array to store all products
        $allProducts = [];

        // Loop through each sub-section and get the associated products
        foreach ($sub_sections as $sub_section) {
            // Get the products for the current sub-section
            $section_products = Product::where('section_id', $sub_section->id)->get();

            // If products exist for this sub-section, apply the ProductResource and merge them into the $allProducts array
            if ($section_products->isNotEmpty()) {
                // Merge products of this sub-section into the allProducts array
                $allProducts = array_merge($allProducts, productsResource::collection($section_products)->toArray(request()));
            }
        }

        // If no products were found for the sub-sections, return a 404 response
        if (empty($allProducts)) {
            return response()->json([
                'code' => 404,
                'message' => 'not found ',
            ], 404);
        }

        // Return a successful response with the sub-sections and all products in one list
        return response()->json([
            'data' => [
                'sec_name' =>  $main_section->name,
                'sub_sections' => sectionsResource::collection($sub_sections),  // Apply resource to the sub-sections
                'products' => $allProducts,  // Return all products in one list
            ],
            'code' => 200,
            'message' => 'Sub-sections and all products found successfully',
        ], 200);
    }



    public function testPhoto(Request $request)
    {
        dd($request);
    }
}
