<?php

namespace App\Http\Controllers\Back;

use App\Models\SubSection;
use App\Models\Company;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        return view('pages.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $sections = SubSection::all();
        $companies = Company::all();
        return view(
            'pages.products.create',
            [
                'sections' => $sections,
                'companies' => $companies
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('units')->find($id);

        $options = $product->options;

        return view('pages.products.show', ['product' => $product, 'options' => $options]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Product::findOrFail($id);
        $sections = SubSection::all();

        return view('pages.products.edit', ['data' => $data, 'sections' => $sections]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        //
    }
}
