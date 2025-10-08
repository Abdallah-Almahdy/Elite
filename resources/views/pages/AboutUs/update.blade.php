@extends('admin.app')
@section('content')

<div class="container">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100px;">
        <h1>تعديل بيانات الشركة {{ $about->company_name }}</h1>
    </div>

    <form action="{{ route('about.update', $about->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">اسم الشركة</label>
            <input type="text" name="company_name" value="{{ $about->company_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف القصير</label>
            <textarea name="short_description" class="form-control">{{ $about->short_description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف الكامل</label>
            <textarea name="full_description" class="form-control">{{ $about->full_description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">فيسبوك</label>
            <textarea name="facebook" class="form-control">{{ $about->facebook }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">واتساب</label>
            <textarea name="whatsapp" class="form-control">{{ $about->whatsapp }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">انستجرام</label>
            <textarea name="instagram" class="form-control">{{ $about->instagram }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">الموقع</label>
            <textarea name="location" class="form-control">{{ $about->location }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">وقت العمل من</label>
                <input type="time"
                       name="work_from"
                       class="form-control"
                       value="{{ old('work_from', isset($aboutUs) ? $aboutUs->work_from : '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">وقت العمل إلى</label>
                <input type="time"
                       name="work_to"
                       class="form-control"
                       value="{{ old('work_to', isset($aboutUs) ? $aboutUs->work_to : '') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">اللوجو الحالي</label><br>
            @if($about->logo)
                <img src="{{ asset('uploads/about/' . $about->logo) }}" width="100">
            @endif
            <input type="file" name="logo" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label class="form-label">صور إضافية</label>
            <div id="photos-wrapper">
                <input type="file" name="photos[]" class="form-control mb-2" accept="image/*">
            </div>
        </div>

        <div class="mb-3">
            <button type="button" id="add-photo" class="btn btn-sm btn-primary">+ إضافة صورة</button>
        </div>

        <div class="d-flex justify-content-center mb-5">
            <button class="btn btn-primary px-4 py-2" type="submit">تحديث</button>
        </div>
    </form>

    <h3>الصور الحالية</h3><br><br>
    <div class="row">
        @foreach($about->images as $image)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ asset('uploads/about/photos/' . $image->photo) }}" class="card-img-top">
                    <div class="card-body text-center">
                        <form action="{{ route('about.deleteImage', $image->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger p-1"
                                onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5.5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6zm3 .5a.5.5 0 0 1-.5-.5V6a.5.5 0 0 1 1 0v6a.5.5 0 0 1-1 0V6z"/>
                                    <path fill-rule="evenodd"
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1H14a1 1 0 0 1 1 1v1z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<script>
    document.getElementById('add-photo').addEventListener('click', function () {
        let wrapper = document.getElementById('photos-wrapper');
        let input = document.createElement('input');
        input.type = 'file';
        input.name = 'photos[]';
        input.classList.add('form-control', 'mb-2');
        input.accept = "image/*";
        wrapper.appendChild(input);
    });
</script>
@endsection
