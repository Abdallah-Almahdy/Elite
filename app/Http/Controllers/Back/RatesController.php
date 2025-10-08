<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatesController extends Controller
{
    public function index()
    {
        $ratings = Rating::orderBy('created_at', 'desc')->get();

        return view('pages.rates.index', compact('ratings'));
    }
}
