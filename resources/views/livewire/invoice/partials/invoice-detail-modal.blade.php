<div class="modal fade show" id="invoiceModal" tabindex="-1" aria-hidden="true"
    style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue-600 text-white">
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 me-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    تفاصيل الفاتورة
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeInvoiceModal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h6 class="font-bold text-blue-600 mb-3">معلومات الفاتورة</h6>
                        <div class="space-y-2">
                            <div class="flex justify-between"><span class="text-gray-600">رقم الفاتورة:</span><span
                                    class="font-bold">#{{ $selectedInvoice->id }}</span></div>
                            <div class="flex justify-between"><span
                                    class="text-gray-600">التاريخ:</span><span>{{ $selectedInvoice->created_at?->format('Y-m-d') ?? '--' }}</span>
                            </div>
                            <div class="flex justify-between"><span
                                    class="text-gray-600">الوقت:</span><span>{{ $selectedInvoice->created_at?->format('h:i A') ?? '--' }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-gray-600">الحالة:</span><span
                                    class="badge bg-success">مدفوعة</span></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h6 class="font-bold text-blue-600 mb-3">معلومات الكاشير</h6>
                        <div class="space-y-2">
                            <div class="flex justify-between"><span
                                    class="text-gray-600">الكاشير:</span><span>{{ $selectedInvoice->cashier->name ?? '--' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- جدول المنتجات --}}
                <div class="border rounded-lg overflow-hidden mb-3">
                    <div class="bg-gray-100 px-4 py-3">
                        <h6 class="font-bold text-blue-600 mb-0">المنتجات المشتراة</h6>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right">#</th>
                                    <th class="px-4 py-2 text-right">المنتج</th>
                                    <th class="px-4 py-2 text-center">الكمية</th>
                                    <th class="px-4 py-2 text-center">السعر</th>
                                    <th class="px-4 py-2 text-center">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($selectedInvoice->products_json as $index => $product)
                                    <tr>
                                        <td class="px-4 py-2 text-right">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-right">{{ $product['name'] }}</td>
                                        <td class="px-4 py-2 text-center">{{ $product['quantity'] }}</td>
                                        <td class="px-4 py-2 text-center">{{ number_format($product['price'], 2) }} ج.م
                                        </td>
                                        <td class="px-4 py-2 text-center">{{ number_format($product['total'], 2) }} ج.م
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left font-bold">المجموع:</td>
                                    <td class="px-4 py-3 text-center font-bold text-success">
                                        {{ number_format($selectedInvoice->total, 2) }} ج.م</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left font-bold">المدفوع:</td>
                                    <td class="px-4 py-3 text-center font-bold">
                                        {{ number_format($selectedInvoice->paid_amount, 2) }} ج.م</td>
                                </tr>
                                @if ($selectedInvoice->refunded_amount > 0)
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-left font-bold text-red-600">المرتجع:
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-red-600">
                                            {{ number_format($selectedInvoice->refunded_amount, 2) }} ج.م</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-left font-bold">الصافي:</td>
                                        <td class="px-4 py-3 text-center font-bold text-blue-600">
                                            {{ number_format($selectedInvoice->net_total, 2) }} ج.م</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- جدول المدفوعات --}}
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-100 px-4 py-3">
                        <h6 class="font-bold text-blue-600 mb-0">المدفوعات</h6>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right">#</th>
                                    <th class="px-4 py-2 text-right">طريقة الدفع</th>
                                    <th class="px-4 py-2 text-center">المبلغ</th>
                                    <th class="px-4 py-2 text-center">النوع</th>
                                    <th class="px-4 py-2 text-center">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($selectedInvoice->payments as $index => $payment)
                                    <tr>
                                        <td class="px-4 py-2 text-right">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-right">{{ $payment->payment_method }}</td>
                                        <td class="px-4 py-2 text-center">{{ number_format($payment->amount, 2) }} ج.م
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @if ($payment->type == 'payment')
                                                <span class="badge bg-success">دفع</span>
                                            @else
                                                <span class="badge bg-danger">مرتجع</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($selectedInvoice->invoiceReturns->count() > 0)
                    <div class="border rounded-lg overflow-hidden mt-3">
                        <div class="bg-gray-100 px-4 py-3">
                            <h6 class="font-bold text-red-600 mb-0">المنتجات المرتجعة</h6>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-right">#</th>
                                        <th class="px-4 py-2 text-right">المنتج</th>
                                        <th class="px-4 py-2 text-center">الكمية المرتجعة</th>
                                        <th class="px-4 py-2 text-center">السعر</th>
                                        <th class="px-4 py-2 text-center">الإجمالي</th>
                                        <th class="px-4 py-2 text-center">تاريخ الإرجاع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $returnIndex = 1; @endphp
                                    @foreach ($selectedInvoice->invoiceReturns as $return)
                                        @foreach ($return->returnItems as $item)
                                            <tr>
                                                <td class="px-4 py-2 text-right">{{ $returnIndex++ }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    {{ $item->invoiceProduct->product->name ?? '--' }}</td>
                                                <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>
                                                <td class="px-4 py-2 text-center">{{ number_format($item->price, 2) }}
                                                    ج.م</td>
                                                <td class="px-4 py-2 text-center">{{ number_format($item->total, 2) }}
                                                    ج.م</td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $return->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary"
                    wire:click="closeInvoiceModal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="alert('طباعة الفاتورة قيد التطوير')">طباعة
                    الفاتورة</button>
                <button type="button" class="btn btn-warning"
                    wire:click="openReturnModal({{ $selectedInvoice->id }})">إرجاع</button>
            </div>
        </div>
    </div>
</div>
