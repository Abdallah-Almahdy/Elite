<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

class BanaresController extends Controller
{
    public function index()
    {
        return view('pages.banares.index');
    }

    public function create()
    {
        return view('pages.banares.create');
    }
}
