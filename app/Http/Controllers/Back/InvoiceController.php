<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('reports.show');
        return view('pages.invoices.index');
    }


    public function destroy($id)
    {
        $invoice  = Invoice::find($id);

        $invoice->delete();

        return back()->with('success', 'تم حذف الفاتورة بنجاح');
    }
}
