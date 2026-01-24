@extends('admin.app')

@section('content')
    <div class="card card-primary h-100 d-flex flex-column">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="text-center mb-0 fw-bold"><i class="fas fa-eye me-2"></i>عرض الصنف</h5>
        </div>

        <div class="card-body flex-grow-1" style="overflow-y: auto; max-height: calc(100vh - 200px);">
            <!-- الصف الأول: الاسم والوصف والقسم -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold">الاسم</label>
                        <div class="form-control bg-light">{{ $product->name }}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold">الوصف</label>
                        <div class="form-control bg-light">{{ $product->description ?? 'لا يوجد وصف' }}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold">القسم</label>
                        <div class="form-control bg-light">{{ $product->section->name ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <!-- الصف الثاني: الحالة والصورة والشركة -->
            <div class="row">
                <!-- العمود الأول - معلومات الحالة -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-muted">معلومات المنتج</h6>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block">الحالة</small>
                                        <span class="fw-medium {{ $product->active ? 'text-success' : 'text-danger' }}">
                                            {{ $product->active ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block">نوع المنتج</small>
                                        <span
                                            class="fw-medium {{ $product->uses_recipe ? 'text-info' : 'text-secondary' }}">
                                            {{ $product->uses_recipe ? 'مركب' : 'عادي' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block">مخزني</small>
                                        <span class="fw-medium {{ $product->is_stock ? 'text-success' : 'text-danger' }}">
                                            {{ $product->is_stock ? 'نعم' : 'لا' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block">ميزان</small>
                                        <span class="fw-medium {{ $product->is_weight ? 'text-success' : 'text-danger' }}">
                                            {{ $product->is_weight ? 'نعم' : 'لا' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted d-block"> الكمية المتاحة مخزن اساسي</small>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold h5 mb-0">{{ $product->defaultWarehouse->first()->pivot->quantity ?? '0' }}</span>
                                    <span class="text-muted ms-2">وحدة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العمود الثاني - الصورة -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-3 text-muted">صورة المنتج</h6>
                            <div class="p-3">
                                <img class="img-fluid rounded border"
                                    src="{{ $product->photo ? asset('uploads/' . $product->photo) : asset('admin/photo/seo.png') }}"
                                    style="max-height: 180px; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العمود الثالث - الشركة -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">

                        <h6 class="card-title mb-3 text-muted">الشركة المصنعة</h6>
                        <div class="card-body">

                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $product->company->name ?? 'غير محدد' }}</div>
                                    <small class="text-muted">المورد الرئيسي</small>
                                </div>
                            </div>

                            <!-- يمكن إضافة معلومات إضافية هنا -->

                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم الوحدات -->
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-balance-scale me-2"></i>الوحدات</h6>
                    <span class="badge bg-light text-primary">{{ $product->units->count() }} وحدة</span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>اسم الوحدة</th>
                                    @if (!$product->uses_recipe)
                                        <th>معامل التحويل</th>
                                    @endif
                                    <th>سعر البيع</th>
                                    <th>سعر الشراء</th>
                                    <th>الباركود</th>
                                    @if ($product->uses_recipe)
                                        <th>إجمالي التكلفة</th>
                                    @endif
                                    <th>الوحدة الأساسية</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($product->units as $unit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <!-- اسم الوحدة -->
                                        <td>
                                            <span class="fw-bold text-primary">{{ $unit->name }}</span>
                                        </td>

                                        <!-- معامل التحويل -->
                                        @if (!$product->uses_recipe)
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ number_format((float) $unit->pivot->conversion_factor, 2, '.', '') }}</span>
                                            </td>
                                        @endif

                                        <!-- الأسعار -->
                                        <td>
                                            <span
                                                class="fw-bold text-success">{{ number_format($unit->pivot->sallprice, 2) }}
                                                ج.م</span>
                                        </td>

                                        <td>
                                            <span class="fw-bold text-primary">{{ number_format($unit->pivot->price, 2) }}
                                                ج.م</span>
                                        </td>

                                        <!-- الباركود -->
                                        <td>
                                            @php
                                                $pivot = $unit->pivot;
                                                $pivot->refresh();
                                            @endphp
                                            @if ($pivot->barcodes->count() > 0)
                                                <div class="d-flex flex-wrap gap-1 justify-content-center">

                                                    @foreach ($pivot->barcodes as $barcode)
                                                        <span class="badge bg-dark">{{ $barcode->code }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">لا يوجد</span>
                                            @endif
                                        </td>

                                        <!-- إجمالي التكلفة -->
                                        @if ($product->uses_recipe)
                                            <td>
                                                {{-- @php
                                                $totalCost = 0;
                                                foreach($unit->pivot->components as $component) {
                                                    $totalCost += ($component->product->units->first()->pivot->price ?? 0) * $component->quantity;
                                                }
                                            @endphp --}}

                                                <span class="fw-bold text-info">{{ number_format($unit->pivot->price, 2) }}
                                                    ج.م</span>
                                            </td>
                                        @endif

                                        <!-- الوحدة الأساسية -->
                                        <td>
                                            @if ($unit->pivot->is_base)
                                                <span class="badge bg-success">نعم</span>
                                            @else
                                                <span class="badge bg-secondary">لا</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- عرض المكونات للمنتج المركب -->
                                    @if ($product->uses_recipe && $unit->pivot->components->count() > 0)
                                        <tr class="bg-light">
                                            <td colspan="{{ $product->uses_recipe ? 7 : 6 }}">
                                                <div class="border rounded p-3 mt-2">
                                                    <h6 class="fw-semibold text-secondary mb-3">
                                                        <i class="fa fa-utensils me-1"></i>مكونات الوحدة
                                                    </h6>

                                                    <table
                                                        class="table table-sm table-bordered align-middle text-center mb-0">
                                                        <thead class="table-secondary">
                                                            <tr>
                                                                <th>المنتج</th>
                                                                <th>الكمية</th>
                                                                <th>الوحدة</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($unit->pivot->components as $component)
                                                                <tr>
                                                                    <td>{{ $component->product->name ?? 'غير محدد' }}</td>
                                                                    <td>{{ $component->quantity }}</td>
                                                                    <td>{{ $component->componentUnit->unit->name ?? 'غير محدد' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- قسم الخيارات -->
            @if ($product->options->count() > 0)
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-cogs me-2"></i>خيارات المنتج</h6>
                        <span class="badge bg-light text-primary">{{ $product->options->count() }} خيار</span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الخيار</th>
                                        <th>الحالة</th>
                                        <th>قيم الخيار</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($product->options as $option)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $option->name }}</td>
                                            <td>
                                                <span class="badge {{ $option->active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $option->active ? 'مفعل' : 'غير مفعل' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($option->values->count() > 0)
                                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                        @foreach ($option->values as $value)
                                                            <span class="badge bg-secondary">
                                                                {{ $value->name }} ({{ number_format($value->price, 2) }}
                                                                ج.م)
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">لا توجد قيم</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- قسم الإضافات -->
            @if ($product->addsOn->count() > 0)
                <div class="card border-success mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>الإضافات</h6>
                        <span class="badge bg-light text-success">{{ $product->addsOn->count() }} إضافة</span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الإضافة</th>
                                        <th>السعر</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->addsOn as $addon)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $addon->name }}</td>
                                            <td class="text-success fw-bold">{{ number_format($addon->price, 2) }} ج.م
                                            </td>
                                            <td>
                                                <span class="badge {{ $addon->active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $addon->active ? 'مفعل' : 'غير مفعل' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- أزرار التحكم -->
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>

                <div>
                    @can('product.edit')
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i>تعديل المنتج
                        </a>
                    @endcan


                </div>
            </div>
        </div>
    </div>
@endsection
