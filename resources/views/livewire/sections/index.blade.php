<div class="sections-management">
    <!-- الهيدر -->
    <div class="dashboard-header mb-6">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="page-title mb-2">
                    <i class="fas fa-folder-tree me-3"></i>إدارة الأقسام
                </h1>
                <p class="page-subtitle text-muted">تنظيم وإدارة الأقسام الرئيسية والفرعية للمتجر</p>
            </div>
            <div class="col-md-4 text-end">
                @can('section.create')
                <a href="{{ route('sections.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>إضافة قسم جديد
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- بطاقة الجدول -->
    <div class="card modern-card border-0 shadow-lg">
        <div class="card-header bg-transparent py-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-check me-2 text-primary"></i>
                        <span class="text-dark">قائمة الأقسام</span>
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input wire:model.live.debounce.200ms="search" type="text" class="form-control border-start-0" placeholder="ابحث عن قسم...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-head-custom">
                        <tr>
                            <th width="80px" class="text-center py-3">الصورة</th>
                            <th class="py-3">اسم القسم</th>
                            <th width="120px" class="text-center py-3">النوع</th>
                            <th width="200px" class="text-center py-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <!-- القسم الرئيسي -->
                            <tr class="main-section-row">
                                <td class="text-center">
                                    <div class="image-wrapper">
                                        <img class="section-img rounded-circle shadow-sm"
                                             src="{{ asset('uploads/' . ($section->photo ?? 'default-photo.jpg')) }}"
                                             alt="{{ $section->name }}"
                                             onerror="this.src='{{ asset('admin/photo/seo.png') }}'">
                                    </div>
                                </td>
                                <td>
                                    <div class="section-details">
                                        <h6 class="section-name mb-1">{{ $section->name }}</h6>
                                        <small class="text-muted">القسم الرئيسي</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a target="_blank" href="{{ route('sections.main') }}">
                                        <span class="badge-main">
                                            <i class="fas fa-folder me-1"></i>رئيسي
                                        </span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('sectionsInfo', $section->id) }}"
                                           class="btn btn-action btn-view"
                                           data-bs-toggle="tooltip" title="عرض التفاصيل">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="action-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>

                                        @can('section.edit')
                                        <a href="{{ route('sections.edit', $section->id) }}"
                                           class="btn btn-action btn-edit"
                                           data-bs-toggle="tooltip" title="تعديل القسم">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="action-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>
                                        @endcan

                                        @can('section.delete')
                                        <button wire:confirm="هل أنت متأكد من حذف هذا القسم؟"
                                                wire:click="delete({{ $section->id }})"
                                                class="btn btn-action btn-delete"
                                                data-bs-toggle="tooltip" title="حذف القسم">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="action-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <!-- الأقسام الفرعية -->
                            @foreach ($subSections as $subSection)
                                @if ($subSection->main_section_id == $section->id)
                                    <tr class="sub-section-row">
                                        <td class="text-center">
                                            <div class="image-wrapper">
                                                <img class="section-img rounded-circle shadow-sm"
                                                     src="{{ asset('uploads/' . ($subSection->photo ?? 'default-photo.jpg')) }}"
                                                     alt="{{ $subSection->name }}"
                                                     onerror="this.src='{{ asset('admin/photo/seo.png') }}'">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="section-details">
                                                <h6 class="section-name mb-1">
                                                    <i class="fas fa-level-down-alt text-muted me-2 fa-rotate-90"></i>
                                                    {{ $subSection->name }}
                                                </h6>
                                                <small class="text-muted">تابع لـ {{ $section->name }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a target="_blank" href="{{ route('sections.sub') }}">
                                                <span class="badge-sub">
                                                    <i class="fas fa-folder me-1"></i>فرعي
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('sections.show', $subSection->id) }}"
                                                   class="btn btn-action btn-view"
                                                   data-bs-toggle="tooltip" title="عرض التفاصيل">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor" class="action-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </a>

                                                @can('section.edit')
                                                <a href="{{ route('sections.edit', $subSection->id) }}"
                                                   class="btn btn-action btn-edit"
                                                   data-bs-toggle="tooltip" title="تعديل القسم">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor" class="action-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                    </svg>
                                                </a>
                                                @endcan

                                                @can('section.delete')
                                                <button wire:confirm="هل أنت متأكد من حذف هذا القسم؟"
                                                        wire:click="delete({{ $subSection->id }})"
                                                        class="btn btn-action btn-delete"
                                                        data-bs-toggle="tooltip" title="حذف القسم">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor" class="action-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            <!-- فاصل بين المجموعات -->
                            <tr class="section-spacer">
                                <td colspan="4" class="p-2">
                                    <div class="section-divider"></div>
                                </td>
                            </tr>

                        @empty
                            <!-- حالة عدم وجود أقسام -->
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon mb-4">
                                            <i class="fas fa-folder-open fa-4x text-light-gray"></i>
                                        </div>
                                        <h4 class="empty-title text-muted mb-3">لا توجد أقسام</h4>
                                        <p class="empty-text text-muted mb-4">لم يتم إضافة أي أقسام إلى المتجر بعد</p>
                                        @can('section.create')
                                        <a href="{{ route('sections.create') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-plus me-2"></i>إضافة أول قسم
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

      
    </div>
</div>
@section('styles')
<style>
.sections-management {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    padding: 20px;
}

.dashboard-header {
    padding: 2rem 0 1rem;
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

.modern-card {
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
}

.card-header {
    border-bottom: 1px solid #e2e8f0;
}

.search-box .input-group {
    border-radius: 10px;
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

.table-head-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #f1f5f9;
}

.main-section-row {
    background: #ffffff;
    transition: all 0.3s ease;
}

.main-section-row:hover {
    background: #f8fafc;
    transform: translateX(4px);
}

.sub-section-row {
    background: #fafbfc;
    transition: all 0.3s ease;
}

.sub-section-row:hover {
    background: #f1f5f9;
    transform: translateX(8px);
}

.image-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}

.section-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.section-img:hover {
    border-color: #667eea;
    transform: scale(1.1);
}

.section-details {
    padding-left: 10px;
}

.section-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.badge-main {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.badge-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

.badge-sub {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #a0aec0, #718096);
    color: white;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.badge-sub:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(160, 174, 192, 0.3);
    color: white;
    text-decoration: none;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: 1px solid;
}

.btn-view {
    border-color: #4299e1;
    color: #4299e1;
}

.btn-view:hover {
    background: #4299e1;
    color: white;
    transform: translateY(-2px);
}

.btn-edit {
    border-color: #48bb78;
    color: #48bb78;
}

.btn-edit:hover {
    background: #48bb78;
    color: white;
    transform: translateY(-2px);
}

.btn-delete {
    border-color: #f56565;
    color: #f56565;
}

.btn-delete:hover {
    background: #f56565;
    color: white;
    transform: translateY(-2px);
}

.action-icon {
    width: 18px;
    height: 18px;
}

.section-spacer {
    background: transparent;
}

.section-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
    margin: 0.5rem 0;
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
    .sections-management {
        padding: 10px;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .action-buttons {
        gap: 0.25rem;
    }

    .btn-action {
        width: 35px;
        height: 35px;
    }

    .action-icon {
        width: 16px;
        height: 16px;
    }
}

/* تأثيرات تحميل */
.main-section-row, .sub-section-row {
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

    // تأثيرات تفاعلية للصور
    const sectionImages = document.querySelectorAll('.section-img');
    sectionImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });

        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });

    // تأثيرات للأزرار
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.05)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection
