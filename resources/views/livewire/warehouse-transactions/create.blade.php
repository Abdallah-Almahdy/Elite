<div class="card card-primary">
    <div class="card-header">
        <h5 class="text-center">عمل حركة جديدة</h5>
    </div>

    <form wire:submit.prevent="save" role="form">
        <div class="card-body">
            <div class="form-group">
                <label for="warehouse">المخزن</label>
                <select wire:model="warehouse_id" id="warehouse" class="form-control" style="width: 100%;">
                    <option value="" class="text-gray">اختر المخزن...</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('warehouse_id')
                <div class="text-danger">الرجاء اختيار المخزن</div>
            @enderror

            <div class="form-group">
                <label for="transaction_type">نوع الحركة</label>
                <select wire:model="transaction_type_id" id="transaction_type" class="form-control"
                    wire:change="changeNewWarehouseVisibility()" style="width: 100%;">
                    <option value="" class="text-gray">اختر النوع...</option>
                    @foreach ($transactions_types as $transactions_type)
                        <option value="{{ $transactions_type->id }}">{{ __('lan.' . $transactions_type->name) }}</option>
                    @endforeach
                </select>
            </div>
            @error('transaction_type_id')
                <div class="text-danger">الرجاء اختيار نوع الحركة</div>
            @enderror

            <div class="form-group @if ($new_warehouse_visability) block @else hidden @endif "
                wire:key="new-warehouse-field">
                <label for="new_warehouse">المخزن الجديد</label>
                <select wire:model="new_warehouse_id" id="new_warehouse" class="form-control" style="width: 100%;">
                    <option value="" class="text-gray">اختر المخزن...</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>

            @error('new_warehouse_id')
                <div class="text-danger">الرجاء اختيار المخزن</div>
            @enderror



            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <input wire:model="bar_code" wire:keydown.enter.prevent="$refresh" type="text" class="form-control"
                id="bar_code" placeholder="امسح الباركود هنا">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-200">
                    <tr>
                        <th class="px-4 py-2 text-center">الكود</th>
                        <th class="px-4 py-2 text-center">الاسم</th>
                        <th class="px-4 py-2 text-center">كمية الصنف في المخزن</th>
                        <th class="px-4 py-2 text-center">الكمية</th>
                        <th class="px-4 py-2 text-center">خيارات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $index => $product)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $product['bar_code'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $product['name'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $product['current_stock'] }}</td>
                            <td class="px-4 py-2 text-center">
                                <input type="number" wire:model="products.{{ $index }}.quantity"
                                    class="w-20 text-center border rounded" min="1"
                                    max="{{ $product['current_stock'] }}">
                            </td>
                            <td class="px-4 py-2 text-center">


                                <button type="button" wire:click="removeProduct({{ $index }})"
                                    class="   btn btn-outline-danger right fas p-1 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>

                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center bg-gray-100">
                                لا يوجد منتجات مضافة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</div>
