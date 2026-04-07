<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class BanaresController extends Controller
{
    public function index()
    {
        Gate::authorize('advertisement.show');
        return view('pages.banares.index');
    }

    public function create()
    {
        return view('pages.banares.create');
    }
}
