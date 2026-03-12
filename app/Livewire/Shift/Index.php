<?php

namespace App\Livewire\Shift;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    // الفلاتر
    public $searchCashier = '';
    public $status = '';
    public $fromDate = '';
    public $toDate = '';

    // خصائص مودال إغلاق الوردية
    public $showCloseModal = false;
    public $closingShiftId = null;
    public $endCash = null;
    public $returns_count = 0;

    // خصائص مودال كشف الحساب
    public $showStatementModal = false;
    public $selectedShift = null;
    public $statementData = [];

    protected $queryString = [
        'searchCashier' => ['except' => ''],
        'status' => ['except' => ''],
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
    ];

    protected $rules = [
        'endCash' => 'required|numeric|min:0',
    ];

    public function updatedSearchCashier()
    {
        $this->resetPage();
    }
    public function updatedStatus()
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

    public function resetFilters()
    {
        $this->reset(['searchCashier', 'status', 'fromDate', 'toDate']);
        $this->resetPage();
    }

    public function confirmCloseShift($shiftId)
    {
        $shift = Shift::findOrFail($shiftId);
        if ($shift->status === 'closed') {
            session()->flash('error', 'الوردية مغلقة بالفعل');
            return;
        }
        $this->closingShiftId = $shiftId;
        $this->endCash = $shift->end_cash;
        $this->showCloseModal = true;
    }

    public function closeShift()
    {
        $this->validate();
        $shift = Shift::findOrFail($this->closingShiftId);
        if ($shift->status === 'closed') {
            session()->flash('error', 'الوردية مغلقة بالفعل');
            $this->showCloseModal = false;
            return;
        }
        $shift->update([
            'status' => 'closed',
            'end_time' => now(),
            'end_cash' => $this->endCash,
        ]);
        session()->flash('message', 'تم إغلاق الوردية بنجاح');
        $this->showCloseModal = false;
        $this->reset(['closingShiftId', 'endCash']);
    }

    public function deleteShift($shiftId)
    {
        $shift = Shift::findOrFail($shiftId);
        if ($shift->invoices()->count() > 0) {
            session()->flash('error', 'لا يمكن حذف وردية تحتوي على فواتير');
            return;
        }
        $shift->delete();
        session()->flash('message', 'تم حذف الوردية بنجاح');
    }

    /**
     * عرض كشف حساب الوردية في مودال
     */
    public function showStatement($shiftId)
    {
        $this->selectedShift = Shift::with('cashier')->findOrFail($shiftId);

        $this->returns_count = DB::table('invoice_returns')
            ->join('invoices', 'invoices.id', '=', 'invoice_returns.invoice_id')
            ->where('invoices.shift_id', $shiftId)
            ->count();

        // طرق الدفع المتاحة
        $paymentMethods = ['cash', 'credit_card', 'instapay', 'wallet'];

        // إجمالي المبيعات لكل طريقة دفع لهذه الوردية
        $salesPayments = DB::table('invoice_payments')
            ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
            ->where('invoices.shift_id', $shiftId)
            ->selectRaw('invoice_payments.payment_method, sum(invoice_payments.amount) as total')
            ->groupBy('invoice_payments.payment_method')
            ->pluck('total', 'payment_method');

        // إجمالي مدفوعات المرتجعات لكل طريقة دفع لهذه الوردية
        $returnsPayments = DB::table('invoice_payments')
            ->join('invoice_returns', 'invoice_returns.id', '=', 'invoice_payments.invoice_return_id')
            ->join('invoices', 'invoices.id', '=', 'invoice_returns.invoice_id')
            ->where('invoices.shift_id', $shiftId)
            ->selectRaw('invoice_payments.payment_method, sum(invoice_payments.amount) as total')
            ->groupBy('invoice_payments.payment_method')
            ->pluck('total', 'payment_method');

        // بناء مصفوفة البيانات
        $this->statementData = [];
        foreach ($paymentMethods as $method) {
            $sales = $salesPayments[$method] ?? 0;
            $returns = $returnsPayments[$method] ?? 0;
            $this->statementData[$method] = [
                'name'    => $this->getPaymentMethodName($method),
                'sales'   => $sales,
                'returns' => $returns,
                'net'     => $sales - $returns,

            ];
        }

        $this->showStatementModal = true;
    }

    /**
     * الحصول على الاسم العربي لطريقة الدفع
     */
    private function getPaymentMethodName($method)
    {
        $names = [
            'cash'        => 'نقدي',
            'credit_card' => 'بطاقة',
            'instapay'    => 'إنستباي',
            'wallet'      => 'محفظة',
        ];
        return $names[$method] ?? $method;
    }

    public function render()
    {
        $query = Shift::with('cashier', 'safe');

        // تطبيق الفلاتر
        if (!empty($this->searchCashier)) {
            $query->whereHas('cashier', fn($q) => $q->where('name', 'like', '%' . $this->searchCashier . '%'));
        }
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }
        if (!empty($this->fromDate)) {
            $query->whereDate('start_time', '>=', $this->fromDate);
        }
        if (!empty($this->toDate)) {
            $query->whereDate('start_time', '<=', $this->toDate);
        }

        // ترتيب من الأقدم إلى الأحدث
        $shifts = $query->orderBy('start_time', 'asc')->paginate(10);

        // حساب القيم لكل وردية
        foreach ($shifts as $shift) {
            // وارد نقدي: مجموع المدفوعات النقدية للفواتير (مبيعات)
            $cashIn = DB::table('invoice_payments')
                ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->where('invoices.shift_id', $shift->id)
                ->where('invoice_payments.payment_method', 'cash')
                ->whereNull('invoice_payments.invoice_return_id')
                ->sum('invoice_payments.amount');

            // صادر نقدي: مجموع المدفوعات النقدية للمرتجعات
            $cashOut = DB::table('invoice_payments')
                ->join('invoice_returns', 'invoice_returns.id', '=', 'invoice_payments.invoice_return_id')
                ->join('invoices', 'invoices.id', '=', 'invoice_returns.invoice_id')
                ->where('invoices.shift_id', $shift->id)
                ->where('invoice_payments.payment_method', 'cash')
                ->sum('invoice_payments.amount');

            $shift->cash_in = $cashIn;
            $shift->cash_out = $cashOut;
            $shift->net_cash = $cashIn - $cashOut; // النهائي للنقدي
            $shift->difference = ($shift->end_cash ?? 0) - $shift->net_cash;
        }

        return view('livewire.shift.index', compact('shifts'));
    }
}
