@extends('admin.app')
@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary">بيانات الشركة</h1>
    </div>

    @if($about)
    <div class="card shadow-lg border-0 mb-5">
        <div class="row g-0">
            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center bg-light p-4">
                @if($about->logo)
                    <img src="{{ asset('uploads/about/' . $about->logo) }}" alt="Logo" class="img-fluid rounded mb-3" style="max-width:180px;">
                @endif
                <h3 class="fw-bold mb-2">{{ $about->company_name }}</h3>
                <span class="badge bg-success mb-2">{{ $about->experience_years }} سنوات خبرة</span>
                <div class="mt-3">
                    <a href="{{ route('about.edit', $about->id) }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-pencil"></i> تعديل
                    </a>
                    <form action="{{ route('about.destroy', $about->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('هل أنت متأكد من حذف كل بيانات الشركة؟')">
                            <i class="bi bi-trash"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-8 p-4">
                <div class="mb-3">
                    <span class="fw-bold">وصف مختصر:</span>
                    <p class="mb-1">{{ $about->short_description }}</p>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">الوصف الكامل:</span>
                    <p class="mb-1">{{ $about->full_description }}</p>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <span class="fw-bold">العنوان:</span> {{ $about->address }}
                    </div>
                    <div class="col-6">
                        <span class="fw-bold">الموقع:</span> {{ $about->location }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <span class="fw-bold">الهاتف:</span> {{ $about->phone }}
                    </div>
                    <div class="col-6">
                        <span class="fw-bold">الإيميل:</span> {{ $about->email }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <span class="fw-bold">فيسبوك:</span> {{ $about->facebook }}
                    </div>
                    <div class="col-4">
                        <span class="fw-bold">واتساب:</span> {{ $about->whatsapp }}
                    </div>
                    <div class="col-4">
                        <span class="fw-bold">انستجرام:</span> {{ $about->instagram }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <span class="fw-bold">عدد العملاء السعداء:</span> {{ $about->happy_clients }}
                    </div>
                    <div class="col-4">
                        <span class="fw-bold">عدد المشاريع الناجحة:</span> {{ $about->successful_projects }}
                    </div>
                    <div class="col-4">
                        <span class="fw-bold">ساعات العمل:</span> من {{ $about->work_from }} إلى {{ $about->work_to }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <h4 class="fw-bold text-secondary mb-3">الصور الإضافية</h4>
            <div class="row">
                @foreach($about->images as $image)
                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <img src="{{ asset('uploads/about/photos/' . $image->photo) }}" class="card-img-top rounded" style="height:140px;object-fit:cover;">
                        <div class="card-body text-center p-2">
                            <form action="{{ route('about.deleteImage', $image->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                    <i class="bi bi-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
        <div class="alert alert-info text-center">لا توجد بيانات بعد.</div>
        <div class="text-center">
            <a href="{{ route('about.create') }}" class="btn btn-success">إضافة بيانات</a>
        </div>
    @endif
</div>
@endsection
