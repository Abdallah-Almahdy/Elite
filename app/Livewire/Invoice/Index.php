<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\InvoiceReturn;
use App\Models\InvoiceReturnItem;
use App\Models\InvoicePayments;
use App\Models\InvoiceProduct;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $searchId = '';
    public $searchShiftId = '';
    public $fromDate = '';
    public $toDate = '';
    public $returnStatus = '';

    public $selectedInvoice = null;
    public $showInvoiceModal = false;
    public $showReturnModal = false;

    public $returnPayments = []; // مصفوفة طرق الدفع مع المبالغ المرتجعة

    public $returnReason = '';
    public $returnType = 'partial';
    public $returnItems = [];
    public $paymentMethods = [];
    public $invoiceIdForReturn = null;
    public $searchCashier = '';

    protected $queryString = ['searchId', 'searchCashier', 'fromDate', 'toDate', 'returnStatus'];

    public function mount()
    {
        $this->paymentMethods = [
            'cash'         => 'نقدي',
            'credit_card'  => 'بطاقة',
            'instapay'     => 'إنستباي',
            'wallet'       => 'محفظة',
        ];
    }

    // مجموع الأصناف المرتجعة
    public function getItemsTotalProperty()
    {
        return collect($this->returnItems)->sum(fn($item) => (float) $item['total']);
    }

    // مجموع المبالغ المرتجعة من طرق الدفع
    public function getTotalRefundProperty()
    {
        return collect($this->returnPayments)->sum('amount');
    }

    public function updatedSearchId()
    {
        $this->resetPage();
    }

    public function updatedSearchShiftId()
    {
        $this->resetPage();
    }

    public function updatedFromDate()
    {
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->resetPage();
    }

    public function showInvoice($invoiceId)
    {
        $this->selectedInvoice = Invoice::with([
            'cashier',
            'products.product',
            'payments.invoiceReturn',
            'invoiceReturns.returnItems.invoiceProduct.product'
        ])->findOrFail($invoiceId);

        $this->selectedInvoice->products_json = $this->selectedInvoice->products->map(function ($item) {
            $returnedQty = $this->selectedInvoice->invoiceReturns
                ->flatMap(fn($return) => $return->returnItems)
                ->where('invoice_products_id', $item->id)
                ->sum('quantity');

            return [
                'id'                 => $item->id,
                'name'               => $item->product->name ?? '—',
                'quantity'           => $item->quantity,
                'price'              => (float) $item->price,
                'total'              => (float) $item->subtotal,
                'returned_quantity'  => $returnedQty,
                'available_quantity' => $item->quantity - $returnedQty,
            ];
        })->toArray();

        $this->selectedInvoice->paid_amount = $this->selectedInvoice->payments->where('type', 'payment')->sum('amount');
        $this->selectedInvoice->refunded_amount = $this->selectedInvoice->payments->where('type', 'refund')->sum('amount');
        $this->selectedInvoice->net_total = $this->selectedInvoice->paid_amount - $this->selectedInvoice->refunded_amount;

        $this->showInvoiceModal = true;
    }

    public function closeInvoiceModal()
    {
        $this->showInvoiceModal = false;
        $this->selectedInvoice = null;
    }

    public function openReturnModal($invoiceId)
    {
        $this->invoiceIdForReturn = $invoiceId;

        $invoice = Invoice::with([
            'products.product',
            'invoiceReturns.returnItems',
            'payments'
        ])->findOrFail($invoiceId);

        // المنتجات
        $this->returnItems = $invoice->products->map(function ($product) use ($invoice) {
            $returnedQty = $invoice->invoiceReturns
                ->flatMap(fn($return) => $return->returnItems)
                ->where('invoice_products_id', $product->id)
                ->sum('quantity');

            $availableQty = $product->quantity - $returnedQty;

            return [
                'invoice_product_id'       => $product->id,
                'name'                      => $product->product->name ?? 'منتج',
                'max_quantity'              => $availableQty,
                'quantity'                  => 0,
                'price'                     => (float) $product->price,
                'unit_conversion_factor'    => $product->unit_conversion_factor,
                'total'                     => 0,
            ];
        })->toArray();

        // تجميع المدفوعات حسب الطريقة
        $paymentsGrouped = $invoice->payments
            ->where('type', 'payment')
            ->groupBy('payment_method')
            ->map(fn($group) => $group->sum('amount'));

        $refundsGrouped = $invoice->payments
            ->where('type', 'refund')
            ->groupBy('payment_method')
            ->map(fn($group) => $group->sum('amount'));

        $this->returnPayments = [];
        foreach ($this->paymentMethods as $key => $label) {
            $paid = $paymentsGrouped[$key] ?? 0;
            $refunded = $refundsGrouped[$key] ?? 0;
            $available = $paid - $refunded;
            if ($available > 0) {
                $this->returnPayments[$key] = [
                    'label'     => $label,
                    'paid'      => $paid,
                    'refunded'  => $refunded,
                    'available' => $available,
                    'amount'    => 0,
                ];
            }
        }

        $this->returnReason = '';
        $this->returnType = 'partial';

        $this->showReturnModal = true;
        $this->showInvoiceModal = false;

        $this->dispatch('open-return-modal');
    }

    public function closeReturnModal()
    {
        $this->showReturnModal = false;
        $this->returnItems = [];
        $this->returnPayments = [];
        $this->invoiceIdForReturn = null;
    }

    public function updatedReturnItems()
    {
        foreach ($this->returnItems as $index => $item) {
            $quantity = (float) $item['quantity'];
            $price = (float) $item['price'];
            $this->returnItems[$index]['total'] = $quantity * $price;
        }
    }

    public function updatedReturnType()
    {
        if ($this->returnType === 'full') {
            foreach ($this->returnItems as $index => $item) {
                $this->returnItems[$index]['quantity'] = $item['max_quantity'];
            }
            $this->updatedReturnItems();
            $this->dispatch('return-type-changed', returnItems: $this->returnItems);
        }
    }

    public function submitReturn()
    {
        // التحقق من وجود كمية مرتجعة
        $hasItems = collect($this->returnItems)->contains(fn($item) => $item['quantity'] > 0);
        if (!$hasItems) {
            $this->addError('amountError', 'يجب تحديد كمية مرتجعة على الأقل.');
            return;
        }

        // التحقق من أن مجموع المبالغ المرتجعة يساوي إجمالي الأصناف
        if (abs($this->totalRefund - $this->itemsTotal) > 0.01) {
            $this->addError('returnPayments', 'مجموع المبالغ المرتجعة يجب أن يساوي إجمالي الأصناف (' . number_format($this->itemsTotal, 2) . ' ج.م)');
            return;
        }

        // التحقق من كل طريقة ألا يتجاوز المبلغ المتاح
        foreach ($this->returnPayments as $method => $data) {
            if ($data['amount'] > $data['available'] + 0.01) { // تسامح بسيط
                $this->addError("returnPayments.{$method}", "المبلغ يتجاوز المتاح لهذه الطريقة ({$data['available']} ج.م)");
                return;
            }
        }

        $this->validate([
            'invoiceIdForReturn' => 'required|exists:invoices,id',
            'returnReason'       => 'nullable|string|max:255',
            'returnType'         => 'required|in:full,partial',
            'returnItems'        => 'required|array|min:1',
            'returnItems.*.quantity' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::with([
                'products',
                'invoiceReturns.returnItems',
                'payments'
            ])->lockForUpdate()->findOrFail($this->invoiceIdForReturn);

            // التحقق من الكميات المتاحة لكل منتج (نفس الكود السابق)
            $errors = [];
            foreach ($this->returnItems as $requestItem) {
                if ($requestItem['quantity'] == 0) continue;
                $invoiceProduct = $invoice->products->firstWhere('id', $requestItem['invoice_product_id']);
                if (!$invoiceProduct) {
                    $errors[] = "المنتج {$requestItem['name']} غير موجود.";
                    continue;
                }
                $returnedQty = $invoice->invoiceReturns
                    ->flatMap(fn($return) => $return->returnItems)
                    ->where('invoice_products_id', $invoiceProduct->id)
                    ->sum('quantity');
                $availableQty = $invoiceProduct->quantity - $returnedQty;
                if ($requestItem['quantity'] > $availableQty) {
                    $errors[] = "الكمية المطلوبة للمنتج {$requestItem['name']} ({$requestItem['quantity']}) تتجاوز المتاح ({$availableQty}).";
                }
            }

            if (!empty($errors)) {
                DB::rollBack();
                session()->flash('error', implode('<br>', $errors));
                return;
            }

            // إنشاء سجل الإرجاع الرئيسي
            $invoiceReturn = InvoiceReturn::create([
                'invoice_id' => $this->invoiceIdForReturn,
                'user_id'    => auth()->id(),
                'total'      => $this->totalRefund, // استخدام المبلغ المحسوب
                'type'       => $this->returnType,
                'notes'      => $this->returnReason,
            ]);

            // جلب المخزن الرئيسي
            $warehouse = Warehouse::where('is_default', true)->first();
            if (!$warehouse) {
                throw new \Exception('المخزن الرئيسي غير موجود.');
            }

            // إنشاء عناصر الإرجاع وإعادة المخزون
            foreach ($this->returnItems as $item) {
                if ($item['quantity'] > 0) {
                    InvoiceReturnItem::create([
                        'invoice_return_id'   => $invoiceReturn->id,
                        'invoice_products_id' => $item['invoice_product_id'],
                        'quantity'            => $item['quantity'],
                        'price'               => $item['price'],
                        'total'               => $item['quantity'] * $item['price'],
                    ]);

                    $invoiceProduct = InvoiceProduct::with('product')->find($item['invoice_product_id']);
                    if ($invoiceProduct && $invoiceProduct->product->is_stock) {
                        $product = $invoiceProduct->product;
                        if (!$product->uses_recipe) {
                            $quantityToAdd = $item['quantity'] * $invoiceProduct->unit_conversion_factor;
                            $this->incrementProductStock($product->id, $warehouse->id, $quantityToAdd);
                        } else {
                            $productUnit = $product->units()
                                ->wherePivot('conversion_factor', $invoiceProduct->unit_conversion_factor)
                                ->first();
                            if ($productUnit) {
                                foreach ($productUnit->pivot->components as $component) {
                                    $comProduct = $component->product;
                                    $comQuantity = $component->pivot->quantity * $item['quantity'];
                                    $this->incrementProductStock($comProduct->id, $warehouse->id, $comQuantity);
                                }
                            }
                        }
                    }
                }
            }

            // إنشاء حركات الدفع (refund) لكل طريقة
            foreach ($this->returnPayments as $method => $data) {
                if ($data['amount'] > 0) {
                    InvoicePayments::create([
                        'invoice_id'       => $this->invoiceIdForReturn,
                        'payment_method'   => $method,
                        'amount'           => $data['amount'],
                        'type'             => 'refund',
                        'invoice_return_id' => $invoiceReturn->id,
                    ]);
                }
            }

            DB::commit();
            session()->flash('success', 'تمت عملية الإرجاع بنجاح.');
            $this->closeReturnModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ أثناء الإرجاع: ' . $e->getMessage());
        }
    }

    private function incrementProductStock($productId, $warehouseId, $quantity)
    {
        $inventory = WarehouseProduct::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();
        if ($inventory) {
            $inventory->increment('quantity', $quantity);
        } else {
            WarehouseProduct::create([
                'warehouse_id' => $warehouseId,
                'product_id'   => $productId,
                'quantity'     => $quantity,
            ]);
        }
    }

    public function deleteInvoice($id)
    {
        $invoice = Invoice::with('invoiceReturns', 'payments')->find($id);
        if (!$invoice) {
            session()->flash('error', 'الفاتورة غير موجودة.');
            return;
        }

        if ($invoice->invoiceReturns()->count() > 0 || $invoice->payments()->count() > 0) {
            session()->flash('error', 'لا يمكن حذف الفاتورة لوجود مرتجعات أو مدفوعات مرتبطة بها.');
            return;
        }

        try {
            $invoice->delete();
            session()->flash('success', 'تم حذف الفاتورة بنجاح.');
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $invoices = Invoice::with([
            'products.product',
            'cashier',
            'payments'
        ])
            ->withCount('invoiceReturns')
            ->when($this->searchId, fn($q) => $q->where('id', $this->searchId))
            ->when($this->searchCashier, fn($q) => $q->whereHas('cashier', fn($q) => $q->where('name', 'like', '%' . $this->searchCashier . '%')))
            ->when($this->returnStatus === 'returned', fn($q) => $q->has('invoiceReturns'))
            ->when($this->returnStatus === 'not_returned', fn($q) => $q->doesntHave('invoiceReturns'))
            ->when($this->fromDate && $this->toDate, function ($q) {
                $q->whereBetween('created_at', [
                    $this->fromDate . ' 00:00:00',
                    $this->toDate . ' 23:59:59'
                ]);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        $invoices->getCollection()->transform(function ($invoice) {
            $invoice->products_json = $invoice->products->map(fn($item) => [
                'id'       => $item->id,
                'name'     => $item->product->name ?? '—',
                'quantity' => $item->quantity,
                'price'    => (float) $item->price,
                'total'    => (float) $item->subtotal,
            ]);

            $invoice->paid_amount = $invoice->payments->where('type', 'payment')->sum('amount');
            $invoice->refunded_amount = $invoice->payments->where('type', 'refund')->sum('amount');
            $invoice->net_total = $invoice->paid_amount - $invoice->refunded_amount;

            return $invoice;
        });

        return view('livewire.invoice.index', compact('invoices'));
    }
}
