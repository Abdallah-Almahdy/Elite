<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function index()
    {
        $complaints = ContactUs::with('user')->orderBy('created_at', 'desc')->get();

        return view('pages.ContactUs.index', compact('complaints'));
    }
}
