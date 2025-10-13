<div>
    <div class="card card-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="text-center mb-0">
                <i class="fas fa-folder-plus me-2"></i>إضافة قسم جديد
            </h5>
        </div>

        <form wire:submit="create" enctype="multipart/form-data" role="form">
            <div class="card-body">
                <!-- الاسم -->
                <div class="form-group mb-4">
                    <label for="name" class="form-label fw-bold">اسم القسم <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-tag text-primary"></i>
                        </span>
                        <input wire:model="name" type="text" class="form-control" id="name" placeholder="أدخل اسم القسم">
                    </div>
                    @error('name')
                        <div class="text-danger small mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- الوصف -->
                <div class="form-group mb-4">
                    <label for="description" class="form-label fw-bold">الوصف</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light align-items-start">
                            <i class="fas fa-align-left text-info mt-1"></i>
                        </span>
                        <textarea wire:model="description" class="form-control" id="description"
                                  rows="3" placeholder="أدخل وصف القسم (اختياري)"></textarea>
                    </div>
                </div>

                <!-- نوع القسم -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">نوع القسم <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-layer-group text-warning"></i>
                                </span>
                                <select wire:model="sectionType" id="sectionType" class="form-control">
                                    <option value="">اختر نوع القسم</option>
                                    <option value="main">رئيسي</option>
                                    <option value="sub">فرعي</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- القسم الرئيسي (يظهر فقط إذا كان النوع فرعي) -->
                    <div class="col-md-6" id="sectionDiv" style="{{ $sectionType == 'sub' ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label for="section" class="form-label fw-bold">القسم الرئيسي <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-folder text-success"></i>
                                </span>
                                <select wire:model="mainSection" id="section" class="form-control">
                                    <option value="">اختر القسم الرئيسي</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('section_id')
                                <div class="text-danger small mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>الرجاء اختيار القسم الرئيسي
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الصورة -->
                <div class="form-group mb-4">
                    <label for="photo" class="form-label fw-bold">صورة القسم</label>
                    <div class="file-upload-container">
                        <input wire:model="photo" class="form-control" type="file" id="photo" accept="image/*">
                    </div>
                    @error('photo')
                        <div class="text-danger small mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    <!-- معاينة الصورة -->
                    @if ($photo)
                        <div class="mt-3">
                            <p class="text-success mb-2">
                                <i class="fas fa-image me-1"></i>معاينة الصورة:
                            </p>
                            <img class="img-thumbnail border-success"
                                 src="{{ $photo->temporaryUrl() }}"
                                 style="max-height: 150px; max-width: 100%;">
                        </div>
                    @endif
                </div>

                <!-- رسالة النجاح -->
                @if (session('done'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">تمت العملية بنجاح!</h6>
                                <p class="mb-0">تم اضافة قسم جديد بنجاح</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- زر الإرسال -->
            <div class="card-footer bg-light">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>حفظ القسم
                </button>
                <button type="button" class="btn btn-outline-secondary px-4" onclick="resetForm()">
                    <i class="fas fa-redo me-2"></i>إعادة تعيين
                </button>
            </div>
        </form>
    </div>
</div>

@section('styles')
<style>
.card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    padding: 1.25rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.input-group-text {
    border-right: none;
    background: #f8f9fa;
}

.form-control {
    border-left: none;
    border-right: 1px solid #ced4da;
}

.form-control:focus {
    box-shadow: none;
    border-color: #ced4da;
}

.file-upload-container {
    position: relative;
}

.file-upload-container::before {
    content: "\f093";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 1;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.alert {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.img-thumbnail {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.img-thumbnail:hover {
    transform: scale(1.02);
}

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .card-footer {
        text-align: center;
    }

    .card-footer .btn {
        margin-bottom: 0.5rem;
        width: 100%;
    }
}
</style>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // التحكم في إظهار/إخفاء قسم القسم الرئيسي
    const sectionTypeSelect = document.getElementById('sectionType');
    const sectionDiv = document.getElementById('sectionDiv');

    if (sectionTypeSelect && sectionDiv) {
        sectionTypeSelect.addEventListener('change', function() {
            if (this.value === 'sub') {
                sectionDiv.style.display = 'block';
            } else {
                sectionDiv.style.display = 'none';
            }
        });
    }

    // تأثيرات للعناصر
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});

// دالة إعادة تعيين النموذج
function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع البيانات المدخلة.')) {
        // إعادة تعيين الحقول
        const form = document.querySelector('form');
        form.reset();

        // إخفاء قسم القسم الرئيسي
        const sectionDiv = document.getElementById('sectionDiv');
        if (sectionDiv) {
            sectionDiv.style.display = 'none';
        }

        // إعادة تعيين معاينة الصورة
        const photoPreview = document.querySelector('.img-thumbnail');
        if (photoPreview) {
            photoPreview.style.display = 'none';
        }
    }
}

// Livewire listener للتغييرات
document.addEventListener('livewire:load', function() {
    Livewire.on('sectionTypeChanged', (value) => {
        const sectionDiv = document.getElementById('sectionDiv');
        if (sectionDiv) {
            sectionDiv.style.display = value === 'sub' ? 'block' : 'none';
        }
    });
});
</script>
@endsection
