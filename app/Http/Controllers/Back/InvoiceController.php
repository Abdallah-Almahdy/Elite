<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {

        $searchId = $request->get('id');
        $invoices = Invoice::when($searchId, function ($query) use ($searchId) {
            $query->where('id', $searchId);
        })->paginate(20);
        return view('pages.invoices.index', compact('invoices'));
    }
}
