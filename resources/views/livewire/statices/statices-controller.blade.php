<div class="dashboard-container">
    <!-- العنوان الرئيسي -->
    <div class="dashboard-header mb-5">
        <h2 class="text-center text-gradient mb-3">
            <i class="fas fa-chart-line me-3"></i>لوحة التحكم والإحصائيات
        </h2>
        <p class="text-center text-muted">نظرة عامة على أداء المتجر وإحصائيات المبيعات</p>
    </div>

    <!-- فلترة التاريخ -->
    <div class="date-filter-section mb-5">
        <div class="card shadow-sm border-0">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="mb-3 text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>فلترة الإحصائيات حسب التاريخ
                        </h6>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="startDate" class="form-label fw-bold text-muted">من تاريخ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-start text-primary"></i>
                                        </span>
                                        <input type="date" id="startDate" wire:model="startDate" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="endDate" class="form-label fw-bold text-muted">إلى تاريخ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-end text-primary"></i>
                                        </span>
                                        <input type="date" id="endDate" wire:model="endDate" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button wire:click="updateMetricsByDate" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>تطبيق
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="date-range-info bg-light-primary rounded p-3">
                            <i class="fas fa-info-circle fa-2x text-primary mb-2"></i>
                            <p class="mb-0 small text-muted">اختر نطاق تاريخ لعرض الإحصائيات المطلوبة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات الطلبات -->
    <div class="stats-section mb-5">
        <h4 class="section-title mb-4">
            <i class="fas fa-shopping-cart me-2"></i>إحصائيات الطلبات
        </h4>
        <div class="row">
            <!-- إجمالي الطلبات -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon orders-icon mb-3">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3 class="stat-number text-primary mb-2">{{ $orders ?? 0 }}</h3>
                        <h6 class="stat-title text-muted mb-3">إجمالي الطلبات</h6>
                        <a href="{{ route('statices.orders') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>

            <!-- الطلبات الناجحة -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon success-icon mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="stat-number text-success mb-2">{{ $successOrders ?? 0 }}</h3>
                        <h6 class="stat-title text-muted mb-3">الطلبات الناجحة</h6>
                        <a href="{{ route('orders.successed') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>

            <!-- الطلبات الفاشلة -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon failed-icon mb-3">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h3 class="stat-number text-danger mb-2">{{ $faildOrders ?? 0 }}</h3>
                        <h6 class="stat-title text-muted mb-3">الطلبات الفاشلة</h6>
                        <a href="{{ route('orders.faild') }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>

            <!-- المستخدمين -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon users-icon mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="stat-number text-warning mb-2">{{ $users }}</h3>
                        <h6 class="stat-title text-muted mb-3">إجمالي المستخدمين</h6>
                        <a href="{{ route('statices.users') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات المتجر -->
    <div class="stats-section mb-5">
        <h4 class="section-title mb-4">
            <i class="fas fa-store me-2"></i>إحصائيات المتجر
        </h4>
        <div class="row">
            <!-- الأقسام الرئيسية -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon sections-icon mb-3">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3 class="stat-number text-info mb-2">{{ $sectionsCount ?? 0 }}</h3>
                        <h6 class="stat-title text-muted mb-3">الأقسام الرئيسية</h6>
                        <a href="{{ route('sections.main') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>

            <!-- الأقسام الفرعية -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon subsections-icon mb-3">
                            <i class="fas fa-folder"></i>
                        </div>
                        <h3 class="stat-number text-success mb-2">{{ $subSectionsCount ?? 0 }}</h3>
                        <h6 class="stat-title text-muted mb-3">الأقسام الفرعية</h6>
                        <a href="{{ route('sections.sub') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>

            <!-- المنتجات -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon products-icon mb-3">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3 class="stat-number text-warning mb-2">{{ $productsCount }}</h3>
                        <h6 class="stat-title text-muted mb-3">إجمالي المنتجات</h6>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>

            <!-- التقييمات والمقترحات -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="stat-card card border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon ratings-icon mb-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="stat-number text-danger mb-2">{{ $ratingsCount ?? 0 }}</h3>
                        <h6 class="stat-title text-muted mb-3">التقييمات والمقترحات</h6>
                        <a href="{{ route('statics.ratings') }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="additional-info">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card bg-light-primary border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-chart-pie fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">نسبة النجاح</h6>
                                <p class="mb-0 text-muted">
                                    @php
                                        $totalOrders = ($orders ?? 0) + ($successOrders ?? 0) + ($faildOrders ?? 0);
                                        $successRate = $totalOrders > 0 ? (($successOrders ?? 0) / $totalOrders) * 100 : 0;
                                    @endphp
                                    {{ number_format($successRate, 1) }}% من الطلبات ناجحة
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card bg-light-success border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-sync-alt fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">آخر تحديث</h6>
                                <p class="mb-0 text-muted">
                                    {{ now()->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('styles')
<style>
.dashboard-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 20px;
}

.dashboard-header {
    padding: 2rem 0;
}

.text-gradient {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    border-right: 4px solid #3498db;
    padding-right: 15px;
    background: linear-gradient(45deg, #2c3e50, #3498db);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    background: white;
    position: relative;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.shadow-hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.orders-icon {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.success-icon {
    background: linear-gradient(45deg, #11998e, #38ef7d);
    color: white;
}

.failed-icon {
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    color: white;
}

.users-icon {
    background: linear-gradient(45deg, #f7971e, #ffd200);
    color: white;
}

.sections-icon {
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    color: white;
}

.subsections-icon {
    background: linear-gradient(45deg, #43e97b, #38f9d7);
    color: white;
}

.products-icon {
    background: linear-gradient(45deg, #fa709a, #fee140);
    color: white;
}

.ratings-icon {
    background: linear-gradient(45deg, #a8edea, #fed6e3);
    color: #666;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-title {
    font-size: 1rem;
    font-weight: 500;
}

.date-filter-section .card {
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.bg-light-primary {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb) !important;
}

.bg-light-success {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9) !important;
}

.input-group-text {
    border: none;
    background: #f8f9fa;
}

.form-control {
    border: 1px solid #e9ecef;
    border-left: none;
}

.form-control:focus {
    box-shadow: none;
    border-color: #667eea;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.date-range-info {
    border-radius: 10px;
}

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 10px;
    }

    .stat-number {
        font-size: 2rem;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .section-title {
        font-size: 1.3rem;
    }
}

/* تأثيرات تحميل سلسة */
.stat-card {
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
.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تأثيرات تفاعلية للبطاقات
    const statCards = document.querySelectorAll('.stat-card');

    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // تأثير للزر عند التمرير
    const applyButton = document.querySelector('button[wire\\:click="updateMetricsByDate"]');
    if (applyButton) {
        applyButton.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        applyButton.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }

    // تحديث الوقت الحي
    function updateLiveTime() {
        const timeElement = document.querySelector('.live-time');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toLocaleString('ar-EG');
        }
    }

    setInterval(updateLiveTime, 60000);
    updateLiveTime();
});
</script>
@endsection
