@extends('admin.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-box me-2"></i>معلومات المنتج
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- الصورة -->
                    <div class="col-md-4 text-center mb-4">
                        <div class="product-image-container">
                            <img class="img-fluid rounded shadow"
                                 src="{{ asset('uploads/' . $product->photo) }}"
                                 alt="{{ $product->name }}"
                                 style="max-height: 250px; object-fit: contain;">
                        </div>
                    </div>

                    <!-- المعلومات الأساسية -->
                    <div class="col-md-8">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tag me-2 text-primary"></i>اسم الصنف
                                </div>
                                <div class="info-value">{{ $product->name }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-dollar-sign me-2 text-success"></i>السعر
                                </div>
                                <div class="info-value">
                                    <span class="price-badge">{{ number_format($product->price, 2) }} جنيه</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-align-right me-2 text-info"></i>الوصف
                                </div>
                                <div class="info-value">{{ $product->description ?? 'لا يوجد وصف' }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-folder me-2 text-warning"></i>القسم
                                </div>
                                <div class="info-value">
                                    <span class="badge bg-warning text-dark">{{ $product->section->name }}</span>
                                </div>
                            </div>

                            @if ($product->active)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-boxes me-2 text-danger"></i>الكمية المتاحة
                                </div>
                                <div class="info-value">
                                    <span class="badge bg-danger">{{ $product->qnt }} وحدة</span>
                                </div>
                            </div>
                            @endif

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-barcode me-2 text-secondary"></i>باركود
                                </div>
                                <div class="info-value">{{ $product->bar_code ?? 'غير محدد' }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-utensils me-2 text-success"></i>يحتوي على وصفة
                                </div>
                                <div class="info-value">
                                    <span class="badge {{ $product->uses_recipe ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->uses_recipe ? 'نعم' : 'لا' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- حالة المنتج -->
        <div class="card card-info mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>حالة المنتج
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="status-indicator {{ $product->active ? 'status-active' : 'status-inactive' }}">
                    <i class="fas {{ $product->active ? 'fa-check-circle' : 'fa-times-circle' }} fa-3x mb-3"></i>
                    <h5>{{ $product->active ? 'نشط' : 'غير نشط' }}</h5>
                </div>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="card card-success">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar me-2"></i>معلومات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-icon text-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $options->count() }}</div>
                            <div class="stat-label">خيارات</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon text-success">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">
                                {{ $options->sum(function($option) { return $option->values->count(); }) }}
                            </div>
                            <div class="stat-label">قيم</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- قسم الـ Options -->
@if($options->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-cogs me-2"></i>خيارات المنتج
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($options as $option)
                    <div class="col-md-6 mb-4">
                        <div class="option-card card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>{{ $option->name }}
                                </h6>
                                <span class="badge {{ $option->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $option->active ? 'مفعل' : 'غير مفعل' }}
                                </span>
                            </div>
                            <div class="card-body">
                                @if($option->values->count() > 0)
                                    <div class="values-list">
                                        @foreach($option->values as $value)
                                        <div class="value-item d-flex justify-content-between align-items-center p-2 border-bottom">
                                            <div class="value-info">
                                                <span class="value-name">{{ $value->name }}</span>
                                                @if($value->price_adjustment > 0)
                                                <small class="text-success d-block">
                                                    + {{ number_format($value->price_adjustment, 2) }} جنيه
                                                </small>
                                                @endif
                                            </div>
                                            <span class="badge bg-light text-dark">
                                                {{ $loop->iteration }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                        <p>لا توجد قيم لهذا الخيار</p>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">
                                    <i class="fas fa-list me-1"></i>
                                    {{ $option->values->count() }} قيم
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-body text-center py-5">
                <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد خيارات لهذا المنتج</h5>
                <p class="text-muted">يمكنك إضافة خيارات من خلال تعديل المنتج</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- أزرار التحكم -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning mx-2">
                    <i class="fas fa-edit me-2"></i>تعديل المنتج
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary mx-2">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid #eee;
}

.info-label {
    font-weight: bold;
    color: #555;
}

.info-value {
    color: #333;
}

.price-badge {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: bold;
}

.status-indicator {
    padding: 1.5rem;
    border-radius: 10px;
}

.status-active {
    background: linear-gradient(45deg, #d4edda, #c3e6cb);
    color: #155724;
}

.status-inactive {
    background: linear-gradient(45deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.quick-stats {
    display: grid;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-icon {
    font-size: 2rem;
    margin-left: 1rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.option-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #dee2e6;
}

.option-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.values-list {
    max-height: 200px;
    overflow-y: auto;
}

.value-item {
    transition: background-color 0.2s ease;
}

.value-item:hover {
    background-color: #f8f9fa;
}

.value-name {
    font-weight: 500;
}

.product-image-container {
    border: 3px solid #f8f9fa;
    border-radius: 10px;
    padding: 10px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .stat-item {
        flex-direction: column;
        text-align: center;
    }

    .stat-icon {
        margin-left: 0;
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
