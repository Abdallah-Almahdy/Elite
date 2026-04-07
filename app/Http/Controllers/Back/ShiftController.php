<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShiftController extends Controller
{


    public function index()
    {
        Gate::authorize('reports.show');
        return view('pages.shifts.index');
    }

    public function create()
    {
        // Logic to show form for creating a new shift
    }

    public function store(Request $request)
    {
        // Logic to store a new shift
    }

    public function show($id)
    {
        // Logic to display a specific shift
    }

    public function edit($id)
    {
        // Logic to show form for editing a shift
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific shift
    }

    public function destroy($id)
    {
        // Logic to delete a specific shift
    }
}
