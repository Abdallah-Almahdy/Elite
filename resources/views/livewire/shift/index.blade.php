<div class="card shadow-none">
    <div class="overflow-x-auto rounded-md shadow-sm border border-gray-200">
        <table class="min-w-full divide-y text-center divide-gray-200 text-sm text-right text-gray-700 bg-white">
            <thead class="bg-blue-200 text-gray-700 text-xs font-semibold uppercase">
                <!-- صف الفلاتر (تم تعديل colspan إلى 11) -->
                <tr>
                    <th colspan="11" class="px-3 py-3 bg-blue-50">
                        <div class="flex justify-between items-center gap-2">
                            <div class="flex items-center gap-2">
                                <button wire:click="resetFilters" class="btn btn-outline-danger p-1" title="إعادة تعيين">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <input type="text" wire:model.live.debounce.300ms="searchCashier" placeholder="بحث باسم الكاشير"
                                       class="w-48 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none">
                                <select wire:model.live="status" class="w-36 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                    <option value="">كل الورديات</option>
                                    <option value="open">مفتوحة</option>
                                    <option value="close">مغلقة</option>
                                </select>
                                <label>من</label>
                                <input type="date" wire:model.live="fromDate" class="w-36 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                <label>إلى</label>
                                <input type="date" wire:model.live="toDate" class="w-36 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                            </div>
                        </div>
                    </th>
                </tr>
                <!-- عناوين الأعمدة الجديدة -->
                <tr>
                    <th class="px-3 py-2 border border-gray-200 w-[10px]">#</th>
                    <th class="px-3 py-2 border border-gray-200">الكاشير</th>
                    <th class="px-3 py-2 border border-gray-200">توقيت البدأ</th>
                    <th class="px-3 py-2 border border-gray-200">توقيت الانتهاء</th>
                    <th class="px-3 py-2 border border-gray-200">افتتاحي نقدي</th>
                    <th class="px-3 py-2 border border-gray-200">وارد نقدي</th>
                    <th class="px-3 py-2 border border-gray-200">صادر نقدي</th>
                    <th class="px-3 py-2 border border-gray-200">نهائي للنقدي</th>
                    <th class="px-3 py-2 border border-gray-200">المدفوع</th>
                    <th class="px-3 py-2 border border-gray-200">الفرق</th>
                    <th class="px-3 py-2 border border-gray-200">خيارات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $index => $shift)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 border border-gray-200">{{ $shifts->firstItem() + $index }}</td>
                        <td class="px-3 py-2 border border-gray-200">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold ml-2">
                                    {{ substr($shift->cashier->name ?? 'ك', 0, 1) }}
                                </div>
                                {{ $shift->cashier->name ?? '--' }}
                            </div>
                        </td>
                        <td class="px-3 py-2 border border-gray-200">
                            {{ $shift->start_time ? $shift->start_time->format('Y-m-d h:i A') : '--' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200">
                            {{ $shift->end_time ? $shift->end_time->format('Y-m-d h:i A') : '--' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 font-bold text-green-600">
                            {{ number_format($shift->start_cash ?? 0, 2) }} ج.م
                        </td>
                        <td class="px-3 py-2 border border-gray-200 font-bold text-green-700">
                            {{ number_format($shift->cash_in ?? 0, 2) }} ج.م
                        </td>
                        <td class="px-3 py-2 border border-gray-200 font-bold text-red-600">
                            {{ number_format($shift->cash_out ?? 0, 2) }} ج.م
                        </td>
                        <td class="px-3 py-2 border border-gray-200 font-bold text-purple-600">
                            {{ number_format($shift->cash_net ?? 0, 2) }} ج.م
                        </td>
                        <td class="px-3 py-2 border border-gray-200 font-bold {{ $shift->end_cash ? 'text-blue-600' : 'text-gray-400' }}">
                            {{ $shift->end_cash ? number_format($shift->end_cash, 2) . ' ج.م' : '--' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 font-bold {{ ($shift->difference ?? 0) > 0 ? 'text-green-600' : (($shift->difference ?? 0) < 0 ? 'text-red-600' : 'text-gray-600') }}">
                            {{ $shift->end_cash ? number_format($shift->difference ?? 0, 2) . ' ج.م' : '--' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200">
                            <div class="inline-flex items-center justify-center gap-1">
                                <!-- زر كشف الحساب -->
                                <button type="button" wire:click="showStatement({{ $shift->id }})" class="btn btn-outline-info p-1" title="كشف حساب">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                                    </svg>
                                </button>

                                @if($shift->status == 'open')
                                    <button type="button" wire:click="confirmCloseShift({{ $shift->id }})" class="btn btn-outline-success p-1" title="إغلاق الوردية">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                @endif

                                <button type="button" wire:click="deleteShift({{ $shift->id }})" wire:confirm="هل أنت متأكد من حذف الوردية؟" class="btn btn-outline-danger p-1" title="حذف">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-gray-400 mx-auto mb-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <h5 class="text-gray-500">لا توجد ورديات</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($shifts->hasPages())
        <div class="mt-4">
            {{ $shifts->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    @endif


    <!-- مودال إغلاق الوردية -->
    @if ($showCloseModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h3 class="text-lg font-bold mb-4">إغلاق الوردية</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">الفلوس الفعلية (نهاية الوردية)</label>
                    <input type="number" step="0.01" wire:model="endCash"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('endCash')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('showCloseModal', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg">إلغاء</button>
                    <button wire:click="closeShift" class="px-4 py-2 bg-blue-600 text-white rounded-lg">إغلاق</button>
                </div>
            </div>
        </div>
    @endif

    <!-- مودال كشف الحساب -->
    @if ($showStatementModal && $selectedShift)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <!-- رأس المودال -->
                <div class="flex justify-between items-center border-b p-4 bg-blue-50">
                    <h3 class="text-xl font-bold text-blue-800">
                        كشف حساب الوردية #{{ $selectedShift->id }}
                    </h3>
                    <button wire:click="$set('showStatementModal', false)" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- معلومات الوردية -->
                <div class="p-4 bg-gray-50 border-b">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                        <div><span class="font-semibold">الكاشير:</span> {{ $selectedShift->cashier->name ?? '--' }}
                        </div>
                        <div><span class="font-semibold">بداية:</span>
                            {{ $selectedShift->start_time ? $selectedShift->start_time->format('Y-m-d h:i A') : '--' }}
                        </div>
                        <div><span class="font-semibold">نهاية:</span>
                            {{ $selectedShift->end_time ? $selectedShift->end_time->format('Y-m-d h:i A') : '--' }}
                        </div>
                        <div><span class="font-semibold">الحالة:</span>
                            <span
                                class="px-2 py-1 rounded text-xs {{ $selectedShift->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $selectedShift->status == 'open' ? 'مفتوحة' : 'مغلقة' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- جدول تفاصيل طرق الدفع -->
                <div class="p-4">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-right">طريقة الدفع</th>
                                <th class="px-4 py-2 text-center">المبيعات (فاتورة)</th>
                                <th class="px-4 py-2 text-center">المرتجعات</th>
                                <th class="px-4 py-2 text-center">الصافي</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($statementData as $method => $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium">{{ $data['name'] }}</td>
                                    <td class="px-4 py-2 text-center text-green-600">
                                        {{ number_format($data['sales'], 2) }} ج.م</td>
                                    <td class="px-4 py-2 text-center text-red-600">
                                        {{ number_format($data['returns'], 2) }} ج.م</td>
                                    <td
                                        class="px-4 py-2 text-center font-bold {{ $data['net'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                        {{ number_format($data['net'], 2) }} ج.م
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold">
                            @php
                                $totalSales = array_sum(array_column($statementData, 'sales'));
                                $totalReturns = array_sum(array_column($statementData, 'returns'));
                                $totalNet = array_sum(array_column($statementData, 'net'));
                            @endphp
                            <tr>
                                <td class="px-4 py-2">الإجمالي</td>
                                <td class="px-4 py-2 text-center">{{ number_format($totalSales, 2) }} ج.م</td>
                                <td class="px-4 py-2 text-center">{{ number_format($totalReturns, 2) }} ج.م</td>
                                <td class="px-4 py-2 text-center">{{ number_format($totalNet, 2) }} ج.م</td>
                            </tr>

                        </tfoot>
                    </table>
                </div>

                <!-- زر الإغلاق -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="$set('showStatementModal', false)"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- رسائل النجاح والخطأ -->
    @if (session()->has('message'))
        <div
            class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>
