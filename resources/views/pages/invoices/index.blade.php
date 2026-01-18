@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="mt-4 mb-4">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="card shadow-none">
        <div class="overflow-x-auto rounded-md shadow-sm border border-gray-200">
            <table class="min-w-full divide-y text-center divide-gray-200 text-sm text-right text-gray-700 bg-white">
                <thead class="bg-blue-200 text-gray-700 text-xs font-semibold uppercase">
                    <tr>
                        <th colspan="7" class="px-3 py-3 bg-blue-50 shadow-none border-transparent">
                            <div class="flex justify-between items-center gap-2">
                                <div class="flex justify-between items-center gap-2">
                                    <button type="button" class="btn btn-outline-primary right fas p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>

                                    <button type="button" class="btn btn-outline-info right fas p-1"
                                            title="عرض الفواتير الملغاة">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex justify-between items-center gap-2">
                                    <!-- نموذج البحث -->
                                    <form action="{{ route('invoices.index') }}" method="GET" class="flex items-center gap-2">
                                        <input type="text" name="id"
                                               class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none"
                                               value="{{ request('id') }}" placeholder="رقم الفاتورة">
                                        <label>من</label>
                                        <input type="date" name="from_date"
                                               class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none"
                                               value="{{ request('from_date') }}">
<label>الي</label>
                                        <input type="date" name="to_date"
                                               class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none"
                                               value="{{ request('to_date') }}">

                                        <button type="submit" class="btn btn-outline-primary right fas p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-danger right fas p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th class="px-3 py-2 border border-gray-200 w-[10px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </th>
                        <th class="px-3 py-2 border border-gray-200">رقم الفاتورة</th>
                        <th class="px-3 py-2 border border-gray-200">العنوان</th>
                        <th class="px-3 py-2 border border-gray-200">الكاشير</th>
                        <th class="px-3 py-2 border border-gray-200">المبلغ</th>
                        <th class="px-3 py-2 border border-gray-200">التاريخ</th>
                        <th class="px-3 py-2 border border-gray-200">خيارات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invoices as $index => $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border border-gray-200">{{ $index + 1 }}</td>

                            <td class="px-3 py-2 border border-gray-200">
                                <span class="font-bold text-blue-600">#{{ $invoice->id ?? '--' }}</span>
                            </td>

                            <td class="px-3 py-2 border border-gray-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-4 text-gray-500 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    {{ $invoice->address ?? '--' }}
                                </div>
                            </td>

                            <td class="px-3 py-2 border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-2">
                                        {{ substr($invoice->cashier->name ?? 'ك', 0, 1) }}
                                    </div>
                                    {{ $invoice->cashier->name ?? '--' }}
                                </div>
                            </td>

                            <td class="px-3 py-2 border border-gray-200 font-bold text-green-600">
                                {{ number_format($invoice->total ?? 0, 2) }} ريال
                            </td>

                            <td class="px-3 py-2 border border-gray-200">
                                <div class="flex flex-col">
                                    <span class="text-sm">{{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : ($invoice->date ?? '--') }}</span>
                                    <span class="text-xs text-gray-500">
                                        {{ $invoice->created_at ? $invoice->created_at->format('h:i A') : '--:--' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-3 py-2 border border-gray-200 text-center">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <!-- زر عرض الفاتورة -->
                                    <button type="button"
                                            class="btn btn-outline-info p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#invoiceModal"
                                            onclick="loadInvoiceDetails('{{ $invoice->id }}',
                                                    '{{ $invoice->id }}',
                                                    '{{ $invoice->address ?? '' }}',
                                                    '{{ $invoice->cashier->name ?? '' }}',
                                                    '{{ number_format($invoice->total ?? 0, 2) }}',
                                                    '{{ $invoice->created_at ? $invoice->created_at->format('Y-m-d h:i A') : ($invoice->date ?? '') }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    <!-- زر طباعة الفاتورة -->
                                    <button type="button" class="btn btn-outline-info p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                        </svg>
                                    </button>

                                    <!-- زر حذف الفاتورة -->
                                    <button type="button" class="btn btn-outline-danger p-1"
                                            onclick="confirmDelete('{{ $invoice->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-12 text-gray-400 mx-auto mb-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <h5 class="text-gray-500">لا توجد فواتير</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($invoices->hasPages())
        <div class="mt-4">
            {{ $invoices->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<!-- مودال عرض تفاصيل الفاتورة -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue-600 text-white">
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="size-6 me-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    تفاصيل الفاتورة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h6 class="font-bold text-blue-600 mb-3">معلومات الفاتورة</h6>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">رقم الفاتورة:</span>
                                <span class="font-bold" id="modalInvoiceId">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">التاريخ:</span>
                                <span id="modalDate">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الوقت:</span>
                                <span id="modalTime">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الحالة:</span>
                                <span class="badge bg-success">مدفوعة</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h6 class="font-bold text-blue-600 mb-3">معلومات العميل والكاشير</h6>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">الكاشير:</span>
                                <span id="modalCashier">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">العنوان:</span>
                                <span id="modalAddress">--</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">طريقة الدفع:</span>
                                <span class="badge bg-info">نقدي</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول المنتجات -->
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-100 px-4 py-3">
                        <h6 class="font-bold text-blue-600 mb-0">المنتجات المشتراة</h6>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">المنتج</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">الكمية</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">السعر</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody id="productsTable" class="bg-white divide-y divide-gray-200">
                                <!-- سيتم ملؤه بواسطة JavaScript -->
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left font-bold">المجموع:</td>
                                    <td class="px-4 py-3 text-center font-bold text-success" id="modalTotal">0.00 ريال</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left font-bold">الضريبة (15%):</td>
                                    <td class="px-4 py-3 text-center font-bold" id="modalTax">0.00 ريال</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left font-bold">الإجمالي النهائي:</td>
                                    <td class="px-4 py-3 text-center font-bold text-blue-600 text-lg" id="modalFinalTotal">0.00 ريال</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="size-5 me-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    إغلاق
                </button>
                <button type="button" class="btn btn-primary" onclick="printInvoice()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="size-5 me-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>
                    طباعة الفاتورة
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function loadInvoiceDetails(invoiceId, invoiceNumber, address, cashier, total, dateTime) {
    // تعبئة البيانات الأساسية
    document.getElementById('modalInvoiceId').textContent = '#' + invoiceNumber;
    document.getElementById('modalAddress').textContent = address;
    document.getElementById('modalCashier').textContent = cashier;
    document.getElementById('modalTotal').textContent = total + ' ريال';

    // فصل التاريخ والوقت
    if (dateTime) {
        const parts = dateTime.split(' ');
        document.getElementById('modalDate').textContent = parts[0];
        document.getElementById('modalTime').textContent = parts[1] + ' ' + (parts[2] || '');
    }

    // حساب الضريبة والإجمالي النهائي
    const totalAmount = parseFloat(total.replace(/,/g, ''));
    const tax = totalAmount * 0.15;
    const finalTotal = totalAmount + tax;

    document.getElementById('modalTax').textContent = tax.toFixed(2) + ' ريال';
    document.getElementById('modalFinalTotal').textContent = finalTotal.toFixed(2) + ' ريال';

    // محاكاة جلب بيانات المنتجات
    simulateProductsLoading(invoiceId);
}

function simulateProductsLoading(invoiceId) {
    // محاكاة بيانات المنتجات
    const products = [
        { id: 1, name: 'منتج 1', quantity: 2, price: 50.00, total: 100.00 },
        { id: 2, name: 'منتج 2', quantity: 1, price: 30.00, total: 30.00 },
        { id: 3, name: 'منتج 3', quantity: 3, price: 20.00, total: 60.00 },
    ];

    let productsHTML = '';
    products.forEach((product, index) => {
        productsHTML += `
            <tr>
                <td class="px-4 py-2 text-right">${index + 1}</td>
                <td class="px-4 py-2 text-right">${product.name}</td>
                <td class="px-4 py-2 text-center">${product.quantity}</td>
                <td class="px-4 py-2 text-center">${product.price.toFixed(2)} ريال</td>
                <td class="px-4 py-2 text-center">${product.total.toFixed(2)} ريال</td>
            </tr>
        `;
    });

    document.getElementById('productsTable').innerHTML = productsHTML;
}

function printInvoice() {
    // طباعة الفاتورة
    const printContent = document.getElementById('invoiceModal').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}

function confirmDelete(invoiceId) {
    if (confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) {
        // هنا سيتم إضافة كود الحذف الحقيقي
        console.log('حذف الفاتورة رقم:', invoiceId);
        // يمكنك إضافة AJAX request هنا
        // window.location.href = `/invoices/${invoiceId}/delete`;
    }
}
</script>
@endsection
