<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Section;

class CompanisController extends Controller
{
    public function index()
    {

        $companies = Company::all();
        return view('pages.companies.index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $sections = Section::all();
        $companies = Company::all();
        return view(
            'pages.companies.create',
            [
                'sections' => $sections,
                'companies' => $companies
            ]
        );
    }
}
