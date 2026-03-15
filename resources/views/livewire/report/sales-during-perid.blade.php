<div>
    <div class="container-fluid">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-none">
            <div class="overflow-x-auto rounded-md shadow-sm border border-gray-200">
                <table class="min-w-full divide-y text-center divide-gray-200 text-sm text-right text-gray-700 bg-white">
                    <thead class="bg-blue-200 text-gray-700 text-xs font-semibold uppercase">
                        <!-- صف الفلاتر -->
                        <tr>
                            <th colspan="10" class="px-3 py-3 bg-blue-50">
                                <div class="flex justify-between items-center gap-2 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <input type="date" wire:model.live="fromDate" placeholder="من تاريخ"
                                               class="w-36 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                        <input type="date" wire:model.live="toDate" placeholder="إلى تاريخ"
                                               class="w-36 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                        <select wire:model.live="warehouse_id"
                                            class="w-48 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                            <option value="">جميع المخازن</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model.live="section_id"
                                            class="w-48 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
                                            <option value="">جميع الأقسام</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <button wire:click="resetFilters"
                                            class="btn btn-outline-danger p-1" title="إعادة تعيين">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <!-- عناوين الأعمدة -->
                        <tr>
                            <th class="px-3 py-2 border border-gray-200">#</th>
                            <th class="px-3 py-2 border border-gray-200">الباركود</th>
                            <th class="px-3 py-2 border border-gray-200">اسم المنتج</th>
                            <th class="px-3 py-2 border border-gray-200">الوحدة الأساسية</th>
                            <th class="px-3 py-2 border border-gray-200">المبيعات (الكمية)</th>
                            <th class="px-3 py-2 border border-gray-200">المرتجعات (الكمية)</th>
                            <th class="px-3 py-2 border border-gray-200">صافي الكمية</th>
                            <th class="px-3 py-2 border border-gray-200">صافي القيمة</th>
                            <th class="px-3 py-2 border border-gray-200">خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            <tr wire:key="product-{{ $product->id }}" class="hover:bg-gray-50">
                                <td class="px-3 py-2 border border-gray-200">{{ $products->firstItem() + $index }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ $product->barcode }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ $product->name }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ $product->unit }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ number_format($product->sold_quantity, 2) }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ number_format($product->returned_quantity, 2) }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ number_format($product->net_quantity, 2) }}</td>
                                <td class="px-3 py-2 border border-gray-200">{{ number_format($product->net_value, 2) }} ج.م</td>
                                <td class="px-3 py-2 border border-gray-200">
                                    <button wire:click="showProductDetails({{ $product->id }})"
                                        class="btn btn-outline-info p-1" title="عرض التفاصيل">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-gray-400 mx-auto mb-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <h5 class="text-gray-500">لا توجد منتجات</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($products->hasPages())
            <div class="mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>

    @if ($showProductModal && $selectedProduct)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <!-- رأس المودال -->
                <div class="flex justify-between items-center border-b p-4 bg-blue-50">
                    <h3 class="text-xl font-bold text-blue-800">
                        تفاصيل المنتج: {{ $selectedProduct->name }}
                    </h3>
                    <button wire:click="closeProductModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- معلومات المنتج الأساسية -->
                <div class="p-4 bg-gray-50 border-b">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-semibold">الوحدة الأساسية:</span>
                            <span class="block mt-1">{{ $selectedProduct->productUnits->first()?->unit?->name ?? '--' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">التكلفة:</span>
                            <span class="block mt-1">{{ number_format($selectedProduct->productUnits->first()?->price ?? 0, 2) }} ج.م</span>
                        </div>
                        <div>
                            <span class="font-semibold">سعر البيع:</span>
                            <span class="block mt-1">{{ number_format($selectedProduct->productUnits->first()?->sallprice ?? 0, 2) }} ج.م</span>
                        </div>
                    </div>
                </div>

                <!-- جدول المخازن -->
                <div class="p-4">
                    <h4 class="font-bold mb-3">الكميات في المخازن</h4>
                    <div class="table-responsive">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-right">المخزن</th>
                                    <th class="px-4 py-2 text-center">الكمية</th>
                                    <th class="px-4 py-2 text-center">إجمالي التكلفة</th>
                                    <th class="px-4 py-2 text-center">إجمالي سعر البيع</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($selectedProduct->warehouseProducts as $wp)
                                    <tr>
                                        <td class="px-4 py-2">{{ $wp->warehouse->name ?? '--' }}</td>
                                        <td class="px-4 py-2 text-center">{{ number_format($wp->quantity, 2) }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ number_format($wp->quantity * ($selectedProduct->productUnits->first()?->price ?? 0), 2) }} ج.م
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ number_format($wp->quantity * ($selectedProduct->productUnits->first()?->sallprice ?? 0), 2) }} ج.م
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">لا توجد كميات في المخازن</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- زر الإغلاق -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="closeProductModal"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
