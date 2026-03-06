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
    public $searchShiftId = ''; // إضافة حقل البحث بالوردية
    public $fromDate = '';
    public $toDate = '';
    public $returnStatus = '';
    public $selectedInvoice = null;
    public $showInvoiceModal = false;
    public $showReturnModal = false;

    // بيانات نموذج الإرجاع
    public $returnReason = '';
    public $returnType = 'partial';
    public $returnItems = [];
    public $paymentMethods = [];
    public $selectedPaymentMethod = '';
    public $refundAmount = 0;
    public $originalTotal = 0;
    public $maxRefundAmount = 0;
    public $invoiceIdForReturn = null;

    protected $queryString = ['searchId', 'searchShiftId', 'fromDate', 'toDate', 'returnStatus'];

    public function mount()
    {
        $this->paymentMethods = [
            'cash'         => 'نقدي',
            'credit_card'  => 'بطاقة',
            'instapay'     => 'إنستباي',
            'wallet'       => 'محفظة',
        ];
    }

    public function getItemsTotalProperty()
    {
        return collect($this->returnItems)->sum('total');
    }

    public function updatedSearchId()
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

    /**
     * عرض تفاصيل الفاتورة في مودال
     */
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

    /**
     * فتح مودال الإرجاع
     */
    public function openReturnModal($invoiceId)
    {
        $this->invoiceIdForReturn = $invoiceId;

        $invoice = Invoice::with([
            'products.product',
            'invoiceReturns.returnItems',
            'payments'
        ])->findOrFail($invoiceId);

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

        $this->originalTotal = $invoice->total;
        $totalPaid = $invoice->payments->where('type', 'payment')->sum('amount');
        $totalRefunded = $invoice->payments->where('type', 'refund')->sum('amount');
        $this->maxRefundAmount = $totalPaid - $totalRefunded;
        $this->refundAmount = 0;
        $this->returnReason = '';
        $this->returnType = 'partial';
        $this->selectedPaymentMethod = 'cash';

        $this->showReturnModal = true;
        $this->showInvoiceModal = false;
    }

    public function closeReturnModal()
    {
        $this->showReturnModal = false;
        $this->returnItems = [];
        $this->invoiceIdForReturn = null;
    }

    /**
     * تحديث إجمالي كل صنف عند تغيير الكمية
     */
    public function updatedReturnItems()
    {
        foreach ($this->returnItems as $index => $item) {
            $this->returnItems[$index]['total'] = $item['quantity'] * $item['price'];
        }
    }

    /**
     * عند اختيار إرجاع كلي يتم تحديد كل الكميات تلقائياً
     */
    public function updatedReturnType()
    {
        if ($this->returnType === 'full') {
            foreach ($this->returnItems as $index => $item) {
                $this->returnItems[$index]['quantity'] = $item['max_quantity'];
            }
            $this->updatedReturnItems(); // إعادة حساب totals
        }
    }

    /**
     * تنفيذ عملية الإرجاع
     */
    public function submitReturn()
    {
        $this->validate([
            'invoiceIdForReturn'           => 'required|exists:invoices,id',
            'returnReason'                  => 'nullable|string|max:255',
            'returnType'                     => 'required|in:full,partial',
            'returnItems'                     => 'required|array|min:1',
            'returnItems.*.quantity'         => 'required|numeric|min:0',
            'selectedPaymentMethod'           => 'required|string',
            'refundAmount'                    => 'required|numeric|min:0|max:' . $this->maxRefundAmount,
        ]);

        $hasItems = collect($this->returnItems)->contains(fn($item) => $item['quantity'] > 0);
        if (!$hasItems) {
            session()->flash('error', 'يجب تحديد كمية مرتجعة على الأقل.');
            return;
        }

        DB::beginTransaction();

        try {
            $invoice = Invoice::with([
                'products',
                'invoiceReturns.returnItems',
                'payments'
            ])
                ->lockForUpdate()
                ->findOrFail($this->invoiceIdForReturn);

            // حساب مجموع الأصناف المرتجعة والتحقق من الكميات
            $itemsTotal = 0;
            $errors = [];
            foreach ($this->returnItems as $requestItem) {
                if ($requestItem['quantity'] == 0) continue;

                $invoiceProduct = $invoice->products->firstWhere('id', $requestItem['invoice_product_id']);
                if (!$invoiceProduct) {
                    $errors[] = "المنتج {$requestItem['name']} غير موجود في الفاتورة.";
                    continue;
                }

                $returnedQty = $invoice->invoiceReturns
                    ->flatMap(fn($return) => $return->returnItems)
                    ->where('invoice_products_id', $invoiceProduct->id)
                    ->sum('quantity');

                $availableQty = $invoiceProduct->quantity - $returnedQty;

                if ($requestItem['quantity'] > $availableQty) {
                    $errors[] = "الكمية المطلوبة للمنتج {$requestItem['name']} ({$requestItem['quantity']}) تتجاوز المتاح للإرجاع ({$availableQty}).";
                }

                $itemsTotal += $requestItem['quantity'] * $requestItem['price'];
            }

            if (!empty($errors)) {
                DB::rollBack();
                session()->flash('error', implode('<br>', $errors));
                return;
            }

            // التحقق من أن المبلغ المرتجع <= مجموع الأصناف المرتجعة
            if ($this->refundAmount > $itemsTotal) {
                DB::rollBack();
                session()->flash('error', 'المبلغ المرتجع لا يمكن أن يتجاوز قيمة الأصناف المرتجعة (' . number_format($itemsTotal, 2) . ' ج.م).');
                return;
            }

            // التحقق من أن المبلغ المرتجع لا يتجاوز المدفوع الفعلي (تم بواسطة قاعدة max)
            $totalPaid = $invoice->payments->where('type', 'payment')->sum('amount');
            $totalRefunded = $invoice->payments->where('type', 'refund')->sum('amount');
            $availableRefund = $totalPaid - $totalRefunded;

            if ($this->refundAmount > $availableRefund) {
                DB::rollBack();
                session()->flash('error', 'قيمة المرتجع تتجاوز المبلغ المتاح للرد (' . number_format($availableRefund, 2) . ' ج.م).');
                return;
            }

            // إنشاء سجل الإرجاع الرئيسي
            $invoiceReturn = InvoiceReturn::create([
                'invoice_id' => $this->invoiceIdForReturn,
                'user_id'    => auth()->id(),
                'total'      => $this->refundAmount,
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
                    // إنشاء عنصر الإرجاع
                    $returnItem = InvoiceReturnItem::create([
                        'invoice_return_id'   => $invoiceReturn->id,
                        'invoice_products_id' => $item['invoice_product_id'],
                        'quantity'            => $item['quantity'],
                        'price'                => $item['price'],
                        'total'                => $item['quantity'] * $item['price'],
                    ]);

                    // إعادة المخزون
                    $invoiceProduct = InvoiceProduct::with('product')->find($item['invoice_product_id']);
                    if ($invoiceProduct && $invoiceProduct->product->is_stock) {
                        $product = $invoiceProduct->product;
                        if (!$product->uses_recipe) {
                            // منتج عادي
                            $quantityToAdd = $item['quantity'] * $invoiceProduct->unit_conversion_factor;
                            $this->incrementProductStock($product->id, $warehouse->id, $quantityToAdd);
                        } else {
                            // منتج مركب
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

            // إنشاء حركة الدفع (refund)
            InvoicePayments::create([
                'invoice_id'       => $this->invoiceIdForReturn,
                'payment_method'   => $this->selectedPaymentMethod,
                'amount'           => $this->refundAmount,
                'type'             => 'refund',
                'invoice_return_id' => $invoiceReturn->id,
            ]);

            DB::commit();

            session()->flash('success', 'تمت عملية الإرجاع بنجاح.');
            $this->closeReturnModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ أثناء الإرجاع: ' . $e->getMessage());
        }
    }

    /**
     * زيادة كمية منتج في المخزن
     */
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
                'quantity'        => $quantity,
            ]);
        }
    }

    /**
     * حذف فاتورة (يمنع الحذف إذا كان هناك مدفوعات أو مرتجعات)
     */
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
            ->withCount('invoiceReturns') // إضافة عدد المرتجعات
            ->when($this->searchId, fn($q) => $q->where('id', $this->searchId))
            ->when($this->searchShiftId, fn($q) => $q->where('shift_id', $this->searchShiftId))
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
