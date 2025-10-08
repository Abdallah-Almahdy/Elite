@extends('admin.app')
@section('content')

    <div class="d-flex justify-content-center align-items-center" style="min-height: 100px;">
    <h1>إضافة بيانات الشركة</h1>
</div>

<div class="container">


    <form action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">اسم الشركة</label>
            <input type="text" name="company_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف القصير</label>
            <textarea name="short_description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف الكامل</label>
            <textarea name="full_description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">فيسبوك</label>
            <input type="url" name="facebook" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">واتساب</label>
            <input type="text" name="whatsapp" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">انستجرام</label>
            <input type="url" name="instagram" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">الموقع</label>
            <input type="url" name="location" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="address" class="form-control">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">وقت العمل من</label>
                <input type="time" name="work_from" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">وقت العمل إلى</label>
                <input type="time" name="work_to" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">سنوات الخبرة</label>
                <input type="number" name="experience_years" class="form-control" min="0">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">العملاء السعداء</label>
                <input type="number" name="happy_clients" class="form-control" min="0">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المشاريع الناجحة</label>
                <input type="number" name="successful_projects" class="form-control" min="0">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">اللوجو</label>
            <input type="file" name="logo" class="form-control">
        </div>
{{--
        <div class="mb-3">
            <label class="form-label">صور إضافية</label>
            <input type="file" name="photos[]" class="form-control" multiple>
        </div> --}}



        <div class="mb-3">
            <label class="form-label">صور إضافية</label>
            <div id="photos-wrapper">
                <input type="file" name="photos[]" class="form-control mb-2" accept="image/*">
            </div>
        </div>
        <div class='mb-3'>
            <button type="button" id="add-photo" class="btn btn-sm btn-primary">+ إضافة صورة</button>
        </div>

        <button class="btn btn-success">حفظ</button>

    </form>
</div>
<script>
    document.getElementById('add-photo').addEventListener('click', function () {
        let wrapper = document.getElementById('photos-wrapper');

        // نعمل input جديد
        let input = document.createElement('input');
        input.type = 'file';
        input.name = 'photos[]';
        input.classList.add('form-control', 'mb-2');
        input.accept = "image/*";

        wrapper.appendChild(input);
    });
</script>
@endsection
