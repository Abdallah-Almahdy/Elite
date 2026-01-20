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
        $fromDate = $request->get('from_date');
        $toDate   = $request->get('to_date');
        $invoices = Invoice::with([
            'products.product',   // 👈 eager loading
            'cashier'
        ])
            ->when($searchId, function ($query) use ($searchId) {
                $query->where('id', $searchId);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [
                    $fromDate . ' 00:00:00',
                    $toDate . ' 23:59:59'
                ]);
            })
            ->paginate(20);

        $invoices->getCollection()->transform(function ($invoice) {

            $invoice->products_json = $invoice->products->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'name'     => $item->product->name ?? '—',
                    'quantity' => $item->quantity,
                    'price'    => (float) $item->price,
                    'total'    => (float) $item->subtotal,
                ];
            });

            return $invoice;
        });

     
        return view('pages.invoices.index', compact('invoices'));
    }


    public function destroy($id)
    {
        $invoice  = Invoice::find($id);

        $invoice->delete();

        return back()->with('success', 'تم حذف الفاتورة بنجاح');
    }
}
