<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pages.sections.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Section::class);
        return view('pages.sections.create');
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

        // First, try to find the sub-section by ID
        $subSection = SubSection::find($id);

        // If it's a sub-section, fetch its associated products
        if ($subSection) {
            $section = $subSection;  // Assuming 'section' is the relationship name
            $data = Product::where('section_id', $subSection->id)->get();
        } else {
            // If it's not a sub-section, try finding the main section
            $section = Section::find($id);

            // If a main section is found, get all the sub-sections and their products
            if ($section) {
                $data = collect();  // Initialize an empty collection to store all products

                // Get all sub-sections related to the main section
                $allSubSections = SubSection::where('main_section_id', $section->id)->get();

                // Iterate over each sub-section and collect its products
                foreach ($allSubSections as $subSection) {
                    $data = $data->merge(Product::where('section_id', $subSection->id)->get());
                }
            } else {
                // If neither a sub-section nor a main section is found, handle the error (optional)
                abort(404, 'Section or Subsection not found.');
            }
        }

        // Return the view with the section name and the collected data
        return view('pages.sections.show', ['data' => $data, 'section' => $section->name]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        Gate::authorize('update', Section::class);


        $checkIfManin = Section::find($id);

        if ($checkIfManin) {
            $data = Section::find($id);
        } else {
            $data = SubSection::find($id);
        }
        return view('pages.sections.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(section $section)
    {
        //
    }
}
