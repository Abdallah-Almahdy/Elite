<div>
    <div class="container-fluid">
        <div class="mt-4 mb-4">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <div class="card shadow-none">
            <div class="overflow-x-auto rounded-md shadow-sm border border-gray-200">
                <table class="min-w-full divide-y text-center divide-gray-200 text-sm text-right text-gray-700 bg-white">
                    <thead class="bg-blue-200 text-gray-700 text-xs font-semibold uppercase">
                        <tr>
                            <th colspan="8" class="px-3 py-3 bg-blue-50">
                                <div class="flex justify-between items-center gap-2">
                                    <div></div>
                                    <div class="flex items-center gap-2">
                                        <form wire:submit.prevent class="flex items-center gap-2">
                                            <input type="text" wire:model.live.debounce.300ms="searchId"
                                                class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm"
                                                placeholder="رقم الفاتورة">

                                            <!-- حقل جديد للبحث بالوردية -->
                                            <input type="text" wire:model.live.debounce.300ms="searchCashier"
                                                class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm"
                                                placeholder="الكاشير">

                                            <label>من</label>
                                            <input type="date" wire:model.live="fromDate"
                                                class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                            <label>إلى</label>
                                            <input type="date" wire:model.live="toDate"
                                                class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                            <select wire:model.live="returnStatus"
                                                class="w-40 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                                <option value="">كل الفواتير</option>
                                                <option value="returned">فواتير مرتجعة (كلي/جزئي)</option>
                                                <option value="not_returned">فواتير غير مرتجعة</option>
                                            </select>

                                            <button type="button" class="btn btn-outline-primary p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                </svg>
                                            </button>

                                            <button type="button"
                                                wire:click="$set('searchId', ''); $set('searchShiftId', ''); $set('fromDate', ''); $set('toDate', '')"
                                                class="btn btn-outline-danger p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18 18 6M6 6l12 12" />
                                                </svg>
                                            </button>
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
                            <th class="px-3 py-2 border border-gray-200">الكاشير</th>
                            <th class="px-3 py-2 border border-gray-200">اجمالي الفتورة</th>
                            <th class="px-3 py-2 border border-gray-200">المدفوع</th>
                            <th class="px-3 py-2 border border-gray-200">حالة الإرجاع</th>
                            <th class="px-3 py-2 border border-gray-200">التاريخ</th>
                            <th class="px-3 py-2 border border-gray-200">خيارات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($invoices as $index => $invoice)
                            <tr class="hover:bg-gray-50" wire:key="invoice-{{ $invoice->id }}">
                                <td class="px-3 py-2 border border-gray-200">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 border border-gray-200">
                                    <span class="font-bold text-blue-600">#{{ $invoice->id }}</span>
                                </td>
                                <td class="px-3 py-2 border border-gray-200">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-2">
                                            {{ substr($invoice->cashier->name ?? 'ك', 0, 1) }}
                                        </div>
                                        {{ $invoice->cashier->name ?? '--' }}
                                    </div>
                                </td>
                                <td class="px-3 py-2 border border-gray-200 font-bold text-green-600">
                                    {{ number_format($invoice->total, 2) }} ج.م
                                </td>
                                <td class="px-3 py-2 border border-gray-200">
                                    <div class="flex flex-col">
                                        <span class="text-sm">{{ number_format($invoice->paid_amount, 2) }} ج.م</span>
                                        @if ($invoice->refunded_amount > 0)
                                            <span class="text-xs text-red-500">مرتجع:
                                                {{ number_format($invoice->refunded_amount, 2) }} ج.م</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-2 border border-gray-200">
                                    @if ($invoice->invoice_returns_count > 0)
                                        <span class="badge bg-warning text-dark">مرتجعة</span>
                                        @if ($invoice->invoice_returns_count > 1)
                                            <span
                                                class="badge bg-secondary">{{ $invoice->invoice_returns_count }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">غير مرتجعة</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 border border-gray-200">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm">{{ $invoice->created_at?->format('Y-m-d') ?? '--' }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $invoice->created_at?->format('h:i A') ?? '--' }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2 border border-gray-200 text-center">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        <button type="button" class="btn btn-outline-info p-1"
                                            wire:click="showInvoice({{ $invoice->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-outline-info p-1"
                                            onclick="alert('طباعة الفاتورة قيد التطوير')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning p-1"
                                            wire:click="openReturnModal({{ $invoice->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                            </svg>
                                        </button>
                                        <form wire:submit.prevent="deleteInvoice({{ $invoice->id }})"
                                            onsubmit="return confirm('هل أنت متأكد من حذف الفاتورة؟')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="size-12 text-gray-400 mx-auto mb-3">
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
                {{ $invoices->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    {{-- مودال عرض تفاصيل الفاتورة --}}
    @if ($selectedInvoice && $showInvoiceModal)
        @include('livewire.invoice.partials.invoice-detail-modal')
    @endif

    {{-- مودال الإرجاع --}}
    @if ($showReturnModal && $invoiceIdForReturn)
        @include('livewire.invoice.partials.return-modal')
    @endif
</div>
