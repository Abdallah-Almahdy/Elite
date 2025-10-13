<div class="products-grid-container">
    <div class="container">
        <!-- الهيدر -->
        <div class="products-header mb-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title mb-2">
                        <i class="fas fa-boxes me-3"></i>المتجر
                    </h1>
                    <p class="page-subtitle text-muted">عرض وإدارة جميع منتجات المتجر</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>إضافة منتج جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- فلترة وبحث -->
        <div class="filters-section mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="search-box">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input wire:model="search" wire:keyup="makeSearch" type="text" class="form-control border-start-0" placeholder="ابحث عن منتج...">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="products-count">
                                <span class="badge bg-primary">
                                    <i class="fas fa-box me-1"></i>
                                    {{ $data->count() }} منتج
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- شبكة المنتجات -->
        <div class="products-grid">
            <div class="row g-4">
                @forelse($data as $product)
                <div wire:key="{{ $product->id }}" class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-card card border-0 shadow-hover">
                        <!-- صورة المنتج -->
                        <div class="product-image-container">
                            <img class="product-image"
                                 src="{{ asset('uploads/' . $product->photo) }}"
                                 alt="{{ $product->name }}"
                                 onerror="this.src='{{ asset('admin/photo/seo.png') }}'">

                            <!-- طبقة الإجراءات -->
                            <div class="product-actions">
                                <button wire:confirm="هل أنت متأكد من حذف هذا المنتج؟"
                                        wire:click="delete({{ $product->id }})"
                                        class="btn-action btn-delete"
                                        data-bs-toggle="tooltip" title="حذف المنتج">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="btn-action btn-edit"
                                   data-bs-toggle="tooltip" title="تعديل المنتج">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <a href="{{ route('products.show', $product->id) }}"
                                   class="btn-action btn-view"
                                   data-bs-toggle="tooltip" title="معاينة المنتج">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>

                            <!-- حالة المنتج -->
                            <div class="product-status">
                                @if($product->active)
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle me-1"></i>نشط
                                </span>
                                @else
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-times-circle me-1"></i>غير نشط
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- معلومات المنتج -->
                        <div class="card-body text-center">
                            <h6 class="product-name mb-2">{{ $product->name }}</h6>

                            <div class="product-price mb-2">
                                <span class="price-amount">{{ number_format($product->price, 2) }}</span>
                                <span class="currency">ج.م</span>
                            </div>

                            <div class="product-meta">
                                <div class="meta-item">
                                    <i class="fas fa-folder text-muted me-1"></i>
                                    <small class="text-muted">{{ $product->section->name ?? 'بدون قسم' }}</small>
                                </div>
                                @if($product->qnt > 0)
                                <div class="meta-item">
                                    <i class="fas fa-box text-success me-1"></i>
                                    <small class="text-success">{{ $product->qnt }} وحدة</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- الإجراءات السريعة -->
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="quick-actions">
                                <a href="{{ route('products.show', $product->id) }}"
                                   class="btn btn-outline-primary btn-sm w-100 mb-2">
                                    <i class="fas fa-eye me-1"></i>معاينة
                                </a>
                                <div class="btn-group w-100">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                       class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-edit me-1"></i>تعديل
                                    </a>
                                    <button wire:confirm="هل أنت متأكد من حذف هذا المنتج؟"
                                            wire:click="delete({{ $product->id }})"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i>حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- حالة عدم وجود منتجات -->
                <div class="col-12">
                    <div class="empty-products text-center py-5">
                        <div class="empty-icon mb-4">
                            <i class="fas fa-box-open fa-4x text-light-gray"></i>
                        </div>
                        <h4 class="empty-title text-muted mb-3">لا توجد منتجات</h4>
                        <p class="empty-text text-muted mb-4">لم يتم إضافة أي منتجات إلى المتجر بعد</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>إضافة أول منتج
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>


    </div>
</div>
@section('styles')
<style>
.products-grid-container {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    padding: 20px;
}

.products-header {
    padding: 1rem 0;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(45deg, #2d3748, #4a5568);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    font-size: 1.1rem;
}

.filters-section .card {
    border-radius: 12px;
}

.search-box .input-group {
    border-radius: 8px;
    overflow: hidden;
}

.search-box .form-control {
    border: 1px solid #e2e8f0;
    border-left: none;
}

.search-box .input-group-text {
    border: 1px solid #e2e8f0;
    border-right: none;
    background: #f8fafc;
}

.products-grid {
    margin-top: 1rem;
}

.product-card {
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    background: #ffffff;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    height: 220px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: all 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-delete {
    background: rgba(245, 101, 101, 0.9);
}

.btn-delete:hover {
    background: #f56565;
    transform: scale(1.1);
}

.btn-edit {
    background: rgba(72, 187, 120, 0.9);
}

.btn-edit:hover {
    background: #48bb78;
    transform: scale(1.1);
}

.btn-view {
    background: rgba(66, 153, 225, 0.9);
}

.btn-view:hover {
    background: #4299e1;
    transform: scale(1.1);
}

.product-status {
    position: absolute;
    top: 12px;
    left: 12px;
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background: rgba(72, 187, 120, 0.9);
    color: white;
}

.status-inactive {
    background: rgba(160, 174, 192, 0.9);
    color: white;
}

.product-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 1rem;
    line-height: 1.4;
    height: 2.8rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-price {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.price-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3748;
}

.currency {
    font-size: 0.9rem;
    color: #718096;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-top: 0.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.quick-actions {
    margin-top: 0.5rem;
}

.quick-actions .btn-group {
    gap: 0.25rem;
}

.quick-actions .btn {
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.quick-actions .btn:hover {
    transform: translateY(-2px);
}

.empty-products {
    padding: 3rem 1rem;
}

.empty-icon {
    color: #cbd5e0;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.empty-text {
    font-size: 1rem;
}

.text-light-gray {
    color: #cbd5e0;
}

.shadow-hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .products-grid-container {
        padding: 10px;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .product-card {
        margin-bottom: 1rem;
    }

    .product-actions {
        opacity: 1;
    }

    .quick-actions .btn-group {
        flex-direction: column;
    }

    .products-header .btn-lg {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
}

/* تأثيرات تحميل */
.product-card {
    animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* تناوب في ظهور البطاقات */
.product-card:nth-child(1) { animation-delay: 0.1s; }
.product-card:nth-child(2) { animation-delay: 0.2s; }
.product-card:nth-child(3) { animation-delay: 0.3s; }
.product-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل أدوات التلميح
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // تأثيرات تفاعلية للبطاقات
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // تأثيرات للصور
    const productImages = document.querySelectorAll('.product-image');
    productImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });

        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endsection
