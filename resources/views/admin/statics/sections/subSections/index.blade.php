@extends('admin.app')

@section('content')
<div class="subsections-management">
    <!-- الهيدر -->
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="page-title mb-2">
                    <i class="fas fa-folder me-3"></i>الأقسام الفرعية
                </h1>
                <p class="page-subtitle text-muted">إدارة جميع الأقسام الفرعية في المتجر</p>
            </div>
            <div class="col-md-4 text-end">
                @can('section.create')
                <a href="{{ route('sections.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>إضافة قسم فرعي
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card card border-0 bg-light-info">
                <div class="card-body text-center">
                    <i class="fas fa-folder fa-2x text-info mb-2"></i>
                    <h4 class="text-info mb-1">{{ $subSections->total() }}</h4>
                    <p class="text-muted mb-0">إجمالي الأقسام</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card border-0 bg-light-success">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="text-success mb-1">{{ $subSections->total() }}</h4>
                    <p class="text-muted mb-0">نشطة</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card border-0 bg-light-warning">
                <div class="card-body text-center">
                    <i class="fas fa-link fa-2x text-warning mb-2"></i>
                    <h4 class="text-warning mb-1">{{ $subSections->count() }}</h4>
                    <p class="text-muted mb-0">مرتبطة</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card border-0 bg-light-primary">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                    <h4 class="text-primary mb-1">{{ now()->format('H:i') }}</h4>
                    <p class="text-muted mb-0">آخر تحديث</p>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقة الجدول -->
    <div class="card modern-card border-0 shadow-lg">
        <div class="card-header bg-gradient-info text-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        قائمة الأقسام الفرعية
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-white text-info">
                        <i class="fas fa-link me-1"></i>
                        {{ $subSections->count() }} قسم فرعي
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-head-custom">
                        <tr>
                            <th width="80px" class="text-center py-3">الصورة</th>
                            <th class="py-3 text-start">اسم القسم</th>
                            <th width="120px" class="text-center py-3">النوع</th>
                            <th width="280px" class="text-center py-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subSections as $subSection)
                            <tr class="subsection-row">
                                <td class="text-center">
                                    <div class="image-wrapper">
                                        <img class="subsection-img rounded-circle shadow-sm"
                                             src="{{ asset('uploads/' . ($subSection->photo ?? 'default-photo.jpg')) }}"
                                             alt="{{ $subSection->name }}"
                                             onerror="this.src='{{ asset('admin/photo/seo.png') }}'">
                                        <div class="image-overlay">
                                            <i class="fas fa-external-link-alt"></i>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="subsection-details">
                                        <h6 class="subsection-name mb-1">
                                            <i class="fas fa-level-down-alt text-muted me-2 fa-rotate-90"></i>
                                            {{ $subSection->name }}
                                        </h6>
                                        <div class="subsection-meta">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $subSection->created_at->format('Y-m-d') }}
                                            </small>
                                            @if($subSection->mainSection)
                                            <small class="text-info">
                                                <i class="fas fa-project-diagram me-1"></i>
                                                {{ $subSection->mainSection->name }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge-type badge-sub">
                                        <i class="fas fa-link me-1"></i>
                                        فرعي
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a target="_blank" href="{{ route('sections.show', $subSection->id) }}"
                                           class="btn btn-action btn-view"
                                           data-bs-toggle="tooltip" title="معاينة القسم">
                                            <i class="fas fa-eye me-1"></i>
                                            معاينة
                                        </a>

                                        @can('section.edit')
                                        <a href="{{ route('sections.edit', $subSection->id) }}"
                                           class="btn btn-action btn-edit"
                                           data-bs-toggle="tooltip" title="تعديل القسم">
                                            <i class="fas fa-pen me-1"></i>
                                            تعديل
                                        </a>
                                        @endcan

                                        @can('section.delete')
                                        <button wire:confirm="هل أنت متأكد من حذف هذا القسم الفرعي؟"
                                                wire:click="delete({{ $subSection->id }})"
                                                class="btn btn-action btn-delete"
                                                data-bs-toggle="tooltip" title="حذف القسم">
                                            <i class="fas fa-trash me-1"></i>
                                            حذف
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon mb-4">
                                            <i class="fas fa-folder-minus fa-4x text-light-gray"></i>
                                        </div>
                                        <h4 class="empty-title text-muted mb-3">لا توجد أقسام فرعية</h4>
                                        <p class="empty-text text-muted mb-4">لم يتم إضافة أي أقسام فرعية بعد</p>
                                        @can('section.create')
                                        <a href="{{ route('sections.create') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-plus me-2"></i>إضافة أول قسم فرعي
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- التذييل مع الترقيم -->
        @if($subSections->hasPages())
        <div class="card-footer bg-transparent py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        عرض
                        <strong>{{ $subSections->firstItem() ?? 0 }}</strong>
                        إلى
                        <strong>{{ $subSections->lastItem() ?? 0 }}</strong>
                        من
                        <strong>{{ $subSections->total() }}</strong>
                        قسم فرعي
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $subSections->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.subsections-management {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    padding: 20px;
}

.dashboard-header {
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

.stat-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.bg-light-info {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb) !important;
}

.bg-light-success {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9) !important;
}

.bg-light-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
}

.bg-light-primary {
    background: linear-gradient(135deg, #f3e5f5, #e1bee7) !important;
}

.modern-card {
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.table-head-custom {
    background: inherit;
}

.table-head-custom th {
    color: white;
    font-weight: 600;
    border: none;
    font-size: 0.9rem;
}

.table-container {
    border-radius: 0 0 16px 16px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table > :not(caption) > * > * {
    padding: 1.25rem 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.subsection-row {
    background: #fafbfc;
    transition: all 0.3s ease;
    position: relative;
}

.subsection-row:hover {
    background: #f1f5f9;
    transform: translateX(8px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.subsection-row:last-child td {
    border-bottom: none;
}

.image-wrapper {
    position: relative;
    display: inline-block;
}

.subsection-img {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border: 3px solid #e2e8f0;
    transition: all 0.3s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(160, 174, 192, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: white;
    font-size: 0.8rem;
}

.image-wrapper:hover .image-overlay {
    opacity: 1;
}

.image-wrapper:hover .subsection-img {
    border-color: #a0aec0;
    transform: scale(1.05);
}

.subsection-details {
    padding-left: 10px;
}

.subsection-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.subsection-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.badge-type {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.badge-sub {
    background: linear-gradient(135deg, #a0aec0, #718096);
    color: white;
    box-shadow: 0 2px 8px rgba(160, 174, 192, 0.3);
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    width: 20pc;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    border: 2px solid;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    min-width: 85px;
    justify-content: center;
}

.btn-view {
    border-color: #4299e1;
    color: #4299e1;
    background: transparent;
}

.btn-view:hover {
    background: #4299e1;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
}

.btn-edit {
    border-color: #48bb78;
    color: #48bb78;
    background: transparent;
}

.btn-edit:hover {
    background: #48bb78;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
}

.btn-delete {
    border-color: #f56565;
    color: #f56565;
    background: transparent;
}

.btn-delete:hover {
    background: #f56565;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
}

.empty-state {
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

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .subsections-management {
        padding: 10px;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }

    .btn-action {
        min-width: 100%;
        justify-content: center;
    }

    .subsection-meta {
        flex-direction: column;
        gap: 0.25rem;
        align-items: flex-start;
    }

    .subsection-img {
        width: 45px;
        height: 45px;
    }

    .stat-card .card-body {
        padding: 1rem;
    }

    .stat-card h4 {
        font-size: 1.25rem;
    }
}

/* تأثيرات تحميل */
.subsection-row {
    animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* تناوب في ظهور الصفوف */
.subsection-row:nth-child(odd) {
    animation-delay: 0.1s;
}

.subsection-row:nth-child(even) {
    animation-delay: 0.2s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل أدوات التلميح
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // تأثيرات تفاعلية للصور
    const imageWrappers = document.querySelectorAll('.image-wrapper');
    imageWrappers.forEach(wrapper => {
        wrapper.addEventListener('mouseenter', function() {
            this.querySelector('.subsection-img').style.transform = 'scale(1.05)';
        });

        wrapper.addEventListener('mouseleave', function() {
            this.querySelector('.subsection-img').style.transform = 'scale(1)';
        });
    });

    // تأثيرات للأزرار
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // تحديث الوقت الحي
    function updateLiveTime() {
        const timeElements = document.querySelectorAll('.live-time');
        timeElements.forEach(element => {
            const now = new Date();
            element.textContent = now.toLocaleTimeString('ar-EG');
        });
    }

    setInterval(updateLiveTime, 60000);
    updateLiveTime();
});
</script>
@endsection
