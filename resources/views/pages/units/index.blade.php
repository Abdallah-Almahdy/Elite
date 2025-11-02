@extends('admin.app')

@section('content')
    <!-- رسائل التنبيه -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 me-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 me-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- بطاقة الجدول -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-balance-scale-left me-2"></i>
                    إدارة الوحدات
                </h5>
                <a href="{{ route('units.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    إضافة وحدة جديدة
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- الجدول -->
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" class="text-center py-3">الاسم</th>
                            <th scope="col" class="text-center py-3">الحالة</th>
                            <th scope="col" class="text-center py-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $unit)
                            <tr class="align-middle">
                                <!-- اسم الوحدة -->
                                <td class="text-center py-3">
                                    <span class="fw-semibold">{{ $unit->name }}</span>
                                </td>

                                <!-- حالة الوحدة -->
                                <td class="text-center py-3">
                                    @if ($unit->is_active)
                                        <span class="badge bg-success rounded-pill d-inline-flex align-items-center">
                                            <span class="me-1">●</span>
                                            مفعل
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill d-inline-flex align-items-center">
                                            <span class="me-1">●</span>
                                            غير مفعل
                                        </span>
                                    @endif
                                </td>

                                <!-- أزرار الإجراءات -->
                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- زر التعديل -->
                                        <a href="{{ route('units.edit', $unit->id) }}"
                                           class="btn btn-outline-primary btn-sm d-flex align-items-center"
                                           title="تعديل الوحدة">
                                            <i class="fas fa-edit me-1"></i>
                                            تعديل
                                        </a>

                                        <!-- زر الحذف -->
                                        <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm d-flex align-items-center"
                                                    onclick="return confirm('هل أنت متأكد من حذف الوحدة \"{{ $unit->name }}\"؟')"
                                                    title="حذف الوحدة">
                                                <i class="fas fa-trash me-1"></i>
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                        لا توجد وحدات مضافة حالياً
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- تذييل البطاقة (للعرض إذا كان هناك pagination) -->
        @if($units->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        عرض {{ $units->firstItem() }} - {{ $units->lastItem() }} من أصل {{ $units->total() }} وحدة
                    </div>
                    {{ $units->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- ستايل إضافي بسيط -->
    @section('styles')
    <style>
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .card {
            border-radius: 0.5rem;
        }
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
    </style>
    @endsection
@endsection
