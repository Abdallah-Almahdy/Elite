<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;


class PermissionsController extends Controller
{
    public function index()
    {
        return view('pages.Permissions.index');
    }
}
