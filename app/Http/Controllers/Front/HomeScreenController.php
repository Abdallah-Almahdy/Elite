<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Section;

class HomeScreenController extends Controller
{
    public function support()
    {
        return view('front.support');

    }
    public function index()
    {
        $sections = Section::take(10)->get();
        $products = Product::take(10)->get();
        return view('front.FrontendHome', ['sections' => $sections, 'products' => $products]);
    }


    public function clientShowProductPage($productID)
    {

        $product = Product::find($productID);
        $relevantProducts = product::where(['section_id' => $product->section_id])->take(10)->get();
        return view('front.product.showProduct', ['product' => $product, 'relevantProducts' => $relevantProducts]);
    }


    public function clientSectionProducts($sectionID)
    {
        $products = Product::where(['section_id' => $sectionID])->get();
        return view('front.sections.showAllProducs', ['products' => $products]);
    }


    public function cart()
    {

        return view('front.cart.index');
    }
}
