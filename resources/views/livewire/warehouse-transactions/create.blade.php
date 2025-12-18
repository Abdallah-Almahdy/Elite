<div>
    <!-- رسائل التنبيه -->
    @if (session()->has('success_message'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary shadow-lg">
        <div class="card-header bg-gradient-primary">
            <h5 class="text-center text-white mb-0">
                <i class="fas fa-exchange-alt mr-2"></i>إضافة حركة جديدة
            </h5>
        </div>

        <form wire:submit.prevent="save" role="form">
            <div class="card-body">
                <!-- معلومات الحركة الأساسية -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="warehouse" class="font-weight-bold">
                                <i class="fas fa-warehouse text-primary mr-1"></i>المخزن
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </span>
                                </div>
                                <select wire:model="warehouse_id" id="warehouse"
                                    class="form-control @error('warehouse_id') is-invalid @enderror">
                                    <option value="" class="text-muted">اختر المخزن...</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('warehouse_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="transaction_type" class="font-weight-bold">
                                <i class="fas fa-tasks text-primary mr-1"></i>نوع الحركة
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-cogs text-primary"></i>
                                    </span>
                                </div>
                                <select wire:model="transaction_type_id" id="transaction_type"
                                    class="form-control @error('transaction_type_id') is-invalid @enderror"
                                    wire:change="changeNewWarehouseVisibility()">
                                    <option value="" class="text-muted">اختر النوع...</option>
                                    @foreach ($transactions_types as $transactions_type)
                                        <option value="{{ $transactions_type->id }}">
                                            {{ __('lan.' . $transactions_type->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('transaction_type_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 @if (!$new_warehouse_visability) d-none @endif" wire:key="new-warehouse-field">
                        <div class="form-group">
                            <label for="new_warehouse" class="font-weight-bold">
                                <i class="fas fa-warehouse text-success mr-1"></i>المخزن الجديد
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                    </span>
                                </div>
                                <select wire:model="new_warehouse_id" id="new_warehouse"
                                    class="form-control @error('new_warehouse_id') is-invalid @enderror">
                                    <option value="" class="text-muted">اختر المخزن...</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('new_warehouse_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- زر فتح البحث -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-block" wire:click="toggleSearchModal">
                            <i class="fas fa-search mr-2"></i> بحث متقدم عن المنتجات
                        </button>
                    </div>
                </div>

                <!-- مسح الباركود -->
                <div class="card card-secondary mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-barcode mr-2"></i>مسح الباركود
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="input-group">
                            <input wire:model="bar_code" wire:keydown.enter.prevent="searchByBarcode" type="text"
                                class="form-control" id="bar_code" placeholder="امسح الباركود هنا أو اكتبه يدوياً واضغط Enter">
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            أدخل الباركود واضغط Enter لإضافة المنتج
                        </small>
                    </div>
                </div>

                <!-- نافذة البحث -->
                @if($showSearchModal)
                <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5); z-index: 1050;">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-search mr-2"></i> بحث عن المنتجات
                                </h5>
                                <button type="button" class="close text-white" wire:click="toggleSearchModal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- حقول البحث -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="searchQuery">اسم المنتج</label>
                                            <input type="text" class="form-control"
                                                   wire:model.live="searchQuery"
                                                   placeholder="أدخل اسم المنتج للبحث...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sectionId">القسم</label>
                                            <select class="form-control" wire:model.live="sectionId">
                                                <option value="">جميع الاقسام</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- نتائج البحث -->
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">نتائج البحث ({{ count($searchResults) }})</h6>
@if(count($searchResults) > 0)
    <button type="button"
            class="btn btn-sm {{ $selectAll ? 'btn-primary' : 'btn-outline-primary' }}"
            wire:click="toggleSelectAll">
        <i class="fas {{ $selectAll ? 'fa-check-square' : 'fa-square' }} mr-1"></i>
        {{ $selectAll ? 'إلغاء تحديد الكل' : 'تحديد الكل' }}
    </button>
@endif
                                    </div>

                                    <div class="border rounded">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-hover table-striped mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="40">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="masterCheckbox"
                                                                       wire:model="selectAll">
                                                                <label class="custom-control-label" for="masterCheckbox"></label>
                                                            </div>
                                                        </th>
                                                        <th width="40">#</th>
                                                        <th>الاسم</th>
                                                        <th>القسم</th>
                                                        <th>الوحدة</th>
                                                        <th>الكمية</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($searchResults as $index => $product)
                                                        @php
                                                            $isSelected = isset($selectedProducts[$product->id]);
                                                        @endphp
                                                        <tr class="{{ $isSelected ? 'table-primary' : '' }}">
                                                            <td>
                                                                <div class="custom-control product-checkbox">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input product-checkbox"
                                                                           id="product_{{ $product->id }}"

                                                                           wire:click="toggleProduct({{ $product->id }})">
                                                                    <label class="custom-control-label"
                                                                           for="product_{{ $product->id }}"></label>
                                                                </div>
                                                            </td>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>
                                                                <strong>{{ $product->name }}</strong>
                                                                @if($product->description)
                                                                    <small class="text-muted d-block">{{ Str::limit($product->description, 50) }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $product->section->name ?? 'N/A' }}
                                                            </td>
                                                            <td width="150">
                                                                <select class="form-control form-control-sm"
                                                                        wire:model="units.{{ $product->id }}"
                                                                        wire:change="updateUnit({{ $product->id }}, $event.target.value)">
                                                                    <option value="">اختر الوحدة</option>
                                                                    @if($product->units->isNotEmpty())
                                                                        @foreach($product->units as $unit)
                                                                            <option value="{{ $unit->id }}">
                                                                                {{ $unit->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td width="140">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">

                                                                    </div>
                                                                    <input type="number"
                                                                           class="form-control text-center"
                                                                           wire:model="quantities.{{ $product->id }}"
                                                                           wire:change="updateQuantity({{ $product->id }}, $event.target.value)"
                                                                           min="1"
                                                                           style="width: 60px;">
                                                                    <div class="input-group-append">

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ملخص المنتجات المحددة -->
                                    @if($selectedCount > 0)
                                        <div class="mt-3 p-3 bg-light border rounded">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <h6 class="mb-1">
                                                        <span class="badge badge-primary">{{ $selectedCount }}</span>
                                                        منتج/منتجات محددة
                                                    </h6>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button type="button"
                                                            class="btn btn-primary"
                                                            wire:click="addSelectedFromSearch">
                                                        <i class="fas fa-plus mr-1"></i>
                                                        إضافة المحدد
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-outline-secondary"
                                                            wire:click="$set('selectedProducts', [])">
                                                        <i class="fas fa-times mr-1"></i>
                                                        إلغاء التحديد
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="toggleSearchModal">
                                    إغلاق
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- جدول المنتجات -->
                <div class="card card-secondary">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-boxes mr-2"></i>المنتجات المضافة
                            <span class="badge badge-primary ml-2">{{ count($products) }}</span>
                        </h6>
                        @if (count($products) > 0)
                            <button type="button" wire:click="clearAllProducts"
                                class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt mr-1"></i>حذف الكل
                            </button>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 15%">
                                            <i class="fas fa-barcode mr-1"></i>الكود
                                        </th>
                                        <th class="text-center" style="width: 30%">
                                            <i class="fas fa-cube mr-1"></i>الاسم
                                        </th>
                                        <th class="text-center" style="width: 20%">
                                            <i class="fas fa-box-open mr-1"></i>المخزون الحالي( بالوحده الاساسيه)
                                        </th>
                                        <th class="text-center" style="width: 20%">
                                            <i class="fas fa-sort-amount-up mr-1"></i>الوحدة
                                        </th>
                                        <th class="text-center" style="width: 20%">
                                            <i class="fas fa-sort-amount-up mr-1"></i>الكمية
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                            <i class="fas fa-cog mr-1"></i>خيارات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $index => $product)
                                        <tr class="align-middle">
                                            <td class="text-center font-weight-bold text-primary">
                                                {{ $product['bar_code'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product['name'] }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info p-2" style="font-size: 0.9rem">
                                                    {{ $product['current_stock'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info p-2" style="font-size: 0.9rem">
                                                    {{ $product['unit'] }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <input type="number"
                                                        wire:model="products.{{ $index }}.quantity"
                                                        class="form-control form-control-sm text-center"
                                                        style="width: 70px;" min="1"
                                                        @if ($transaction_type_id != 3 && $transaction_type_id != 1) max="{{ $product['current_stock'] }}" @endif>
                                                </div>

                                                @if (
                                                    $product['quantity'] > $product['current_stock'] &&
                                                        $transaction_type_id != 3 &&
                                                        $transaction_type_id != 1 &&
                                                        $transaction_type_id != null)
                                                    <small class="text-danger d-block mt-1">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        الكمية تتجاوز المخزون المتاح
                                                    </small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" wire:click="removeProduct({{ $index }})"
                                                    class="btn btn-sm btn-outline-danger" title="حذف المنتج">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                                    <p class="h5">لا توجد منتجات مضافة</p>
                                                    <p class="mb-0">قم بمسح الباركود أو استخدام البحث المتقدم لإضافة منتجات</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($products) > 0)
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle text-primary mr-2"></i>
                                        <small class="text-muted">
                                            تم إضافة {{ count($products) }} منتج
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span class="mr-2 font-weight-bold">إجمالي الكميات:</span>
                                        <span class="badge badge-primary p-2">
                                            @php
                                                $totalQuantity = 0;
                                                foreach ($products as $product) {
                                                    $totalQuantity += $product['quantity'] ?? 0;
                                                }
                                                echo $totalQuantity;
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- زر الحفظ -->
            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i>إلغاء
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary px-4"
                            @if (count($products) == 0) disabled @endif>
                            <i class="fas fa-save mr-1"></i>حفظ الحركة
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <!-- إضافة بعض السكريبتات الإضافية -->
    <script>
        window.addEventListener('livewire:init', () => {
            Livewire.on('product-added', () => {
                const barCodeInput = document.getElementById('bar_code');
                barCodeInput.focus();
                barCodeInput.value = '';
                barCodeInput.select();
            });
        });
    </script>
@endpush

@push('styles')
    <!-- إضافة بعض الأنماط الإضافية -->
    <style>
        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #6610f2);
        }

        .table thead th {
            border-top: none;
            font-weight: 600;
            font-size: 0.9rem;
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
            height: auto;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
            transition: all 0.2s;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
            min-width: 60px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 10px 25px;
            font-weight: 600;
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(45deg, #0056b3, #004085);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.6;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            border-bottom: none;
            padding: 1rem 1.25rem;
        }

        .card-footer {
            border-top: 1px solid rgba(0, 0, 0, .125);
            padding: 1rem 1.25rem;
        }

        .input-group-text {
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table td,
        .table th {
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table tbody tr:last-child td {
            border-bottom: 1px solid #dee2e6;
        }

        /* تنسيقات نافذة البحث */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .modal-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .table th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* تأثيرات للصف المحدد */
        .table-primary {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        /* تنسيق للأجهزة المحمولة */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 10px;
            }

            .table-responsive {
                font-size: 12px;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('selectAllToggled', (event) => {
            const isChecked = event.isChecked;

            // تحديث جميع checkboxes المنتجات
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });

            // تحديث الـ master checkbox أيضًا
            const masterCheckbox = document.getElementById('masterCheckbox');
            if (masterCheckbox) {
                masterCheckbox.checked = isChecked;
            }

            // إضافة تأثير مرئي للصفوف
            if (isChecked) {
                document.querySelectorAll('#resultsBody tr').forEach(row => {
                    row.classList.add('table-primary');
                });
            } else {
                document.querySelectorAll('#resultsBody tr').forEach(row => {
                    row.classList.remove('table-primary');
                });
            }
        });

        // بدلاً من forEach على كائن، استخدم Object.keys أو Object.entries
        Livewire.on('selectAllUpdated', (event) => {
            console.log('Select All Updated:', event);

            // التحقق مما إذا كان selectedProducts موجودًا وليس null/undefined
            if (event.selectedProducts && typeof event.selectedProducts === 'object') {
                // استخدام Object.keys للتنقل في خصائص الكائن
                Object.keys(event.selectedProducts).forEach(productId => {
                    const isSelected = event.selectedProducts[productId];
                    const checkbox = document.getElementById('product_' + productId);

                    if (checkbox) {
                        checkbox.checked = isSelected;

                        // تحديث صف الجدول
                        const row = document.getElementById('product_row_' + productId);
                        if (row) {
                            if (isSelected) {
                                row.classList.add('table-primary');
                            } else {
                                row.classList.remove('table-primary');
                            }
                        }
                    }
                });

                // تحديث master checkbox
                const masterCheckbox = document.getElementById('masterCheckbox');
                if (masterCheckbox) {
                    masterCheckbox.checked = event.selectAll;
                }
            }
        });
    });

    // دالة مساعدة لتحديث الـ checkboxes بناءً على selectedProducts (الكائن)
    function updateCheckboxesFromLivewire() {

        const selectedProducts = @json($selectedProducts ?? []);

        // التكرار على جميع المفاتيح في الكائن
        Object.keys(selectedProducts).forEach(productId => {
            const isSelected = selectedProducts[productId];
            const checkbox = document.getElementById('product_' + productId);
            if (checkbox) {
                checkbox.checked = isSelected;
            }
        });
    }

    // تحديث الـ checkboxes عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(updateCheckboxesFromLivewire, 500);
    });
</script>
@endpush
