<div>
    <div class="card card-primary shadow-lg">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>تعديل المنتج - {{ $data->name }}
                </h5>
                <div class="product-badge">
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-hashtag me-1"></i>#{{ $data->id }}
                    </span>
                </div>
            </div>
        </div>

        <form wire:submit="update" enctype="multipart/form-data" role="form">
            <div class="card-body">
                <!-- معلومات المنتج الأساسية -->
                <div class="section-card mb-4">
                    <div class="section-header bg-light-primary rounded p-3 mb-3">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label fw-bold">اسم المنتج <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-tag text-primary"></i>
                                    </span>
                                    <input wire:model="name" type="text" class="form-control" id="name"
                                        placeholder="أدخل اسم المنتج">
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label fw-bold">السعر <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-dollar-sign text-success"></i>
                                    </span>
                                    <input wire:model="price" type="text" class="form-control" id="price"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                                        placeholder="0.00">
                                    <span class="input-group-text bg-light">ج.م</span>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bar_code" class="form-label fw-bold">الباركود</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-barcode text-secondary"></i>
                                    </span>
                                    <input wire:model="bar_code" type="text" class="form-control" id="bar_code"
                                        placeholder="أدخل الباركود">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section" class="form-label fw-bold">القسم <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-folder text-warning"></i>
                                    </span>
                                    <select wire:model="section" id="section" class="form-control">
                                        <option value="" class="text-muted">اختر القسم...</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ $section->id == $data->section_id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('section')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description" class="form-label fw-bold">الوصف</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light align-items-start">
                                        <i class="fas fa-align-left text-info mt-1"></i>
                                    </span>
                                    <textarea wire:model="description" class="form-control" id="description" rows="3"
                                        placeholder="أدخل وصف المنتج..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- إعدادات المخزون والتوفر -->
                <div class="section-card mb-4">
                    <div class="section-header bg-light-warning rounded p-3 mb-3">
                        <h6 class="mb-0 text-warning">
                            <i class="fas fa-boxes me-2"></i>إعدادات المخزون والتوفر
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">حالة المنتج</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-toggle-on text-success"></i>
                                    </span>
                                    <select wire:model="active" id="active" class="form-control">
                                        <option value="1" {{ $data->active ? 'selected' : '' }}>🟢 نشط</option>
                                        <option value="0" {{ !$data->active ? 'selected' : '' }}>🔴 غير نشط
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qnt" class="form-label fw-bold">الكمية <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-boxes text-info"></i>
                                    </span>
                                    <input wire:model="stockQnt" type="number" class="form-control" id="qnt"
                                        min="0" placeholder="أدخل الكمية" required>
                                    <span class="input-group-text bg-light">وحدة</span>
                                </div>
                                @error('qnt')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="offer_rate" class="form-label fw-bold">نسبة الخصم</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-percentage text-danger"></i>
                                    </span>
                                    <input wire:model="offer_rate" type="number" class="form-control"
                                        id="offer_rate" min="0" max="100" placeholder="0">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                                @error('offer_rate')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-check custom-switch-container">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" wire:model="hasRecipe" id="hasRecipe"
                                                class="form-check-input" style="transform: scale(1.3);">
                                            <label for="hasRecipe" class="form-check-label fw-bold">
                                                <i class="fas fa-utensils me-2 text-success"></i>يحتوي على وصفة
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            تفعيل هذه الخاصية إذا كان المنتج يحتاج إلى وصفة تحضير
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="stock-info-card card border-info">
                                <div class="card-body">
                                    <h6 class="card-title text-info">
                                        <i class="fas fa-chart-bar me-2"></i>معلومات المخزون
                                    </h6>
                                    <div class="stock-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">الكمية الحالية:</span>
                                            <span class="stat-value badge bg-primary">{{ $data->qnt }} وحدة</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">الحالة:</span>
                                            <span
                                                class="stat-value badge {{ $data->active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $data->active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- خيارات المنتج -->
                <div class="section-card mb-4">
                    <div class="section-header bg-light-info rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-info">
                                <i class="fas fa-cogs me-2"></i>خيارات المنتج
                            </h6>
                            <button type="button" wire:click="addOption" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>إضافة خيار
                            </button>
                        </div>
                    </div>

                    @if (count($options) > 0)
                        <div class="options-container">
                            @foreach ($options as $optionIndex => $option)
                                <div class="option-card card border-info mb-3">
                                    <div
                                        class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cog me-2"></i>خيار {{ $loop->iteration }}
                                        </h6>
                                        <button type="button" wire:click="removeOption({{ $optionIndex }})"
                                            class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">اسم الخيار</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-tag text-primary"></i>
                                                        </span>
                                                        <input type="text"
                                                            wire:model="options.{{ $optionIndex }}.name"
                                                            class="form-control"
                                                            placeholder="مثل: المقاس، اللون، الخ..." required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-check form-switch mt-4">
                                                        <input type="checkbox"
                                                            wire:model="options.{{ $optionIndex }}.active"
                                                            class="form-check-input" id="active_{{ $optionIndex }}"
                                                            style="transform: scale(1.2);" checked>
                                                        <label class="form-check-label fw-bold"
                                                            for="active_{{ $optionIndex }}">
                                                            مفعل
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- قيم الخيار -->
                                        <div class="values-section">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 text-secondary">
                                                    <i class="fas fa-list me-2"></i>قيم الخيار
                                                </h6>
                                                <button type="button" wire:click="addValue({{ $optionIndex }})"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-plus me-1"></i>إضافة قيمة
                                                </button>
                                            </div>

                                            @if (isset($option['values']) && count($option['values']) > 0)
                                                <div class="values-container">
                                                    @foreach ($option['values'] as $valueIndex => $value)
                                                        <div class="value-card card border-light mb-2">
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label class="form-label">اسم
                                                                                القيمة</label>
                                                                            <div class="input-group">
                                                                                <span
                                                                                    class="input-group-text bg-light">
                                                                                    <i
                                                                                        class="fas fa-pen text-info"></i>
                                                                                </span>
                                                                                <input type="text"
                                                                                    wire:model="options.{{ $optionIndex }}.values.{{ $valueIndex }}.name"
                                                                                    class="form-control"
                                                                                    placeholder="مثل: كبير، أحمر، الخ..."
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">السعر
                                                                                الإضافي</label>
                                                                            <div class="input-group">
                                                                                <span
                                                                                    class="input-group-text bg-light">
                                                                                    <i
                                                                                        class="fas fa-dollar-sign text-success"></i>
                                                                                </span>
                                                                                <input type="number"
                                                                                    wire:model="options.{{ $optionIndex }}.values.{{ $valueIndex }}.price"
                                                                                    class="form-control"
                                                                                    placeholder="0.00" step="0.01"
                                                                                    value="0">
                                                                                <span
                                                                                    class="input-group-text bg-light">+
                                                                                    ج.م</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="form-label">الإجراءات</label>
                                                                            <button type="button"
                                                                                wire:click="removeValue({{ $optionIndex }}, {{ $valueIndex }})"
                                                                                class="btn btn-outline-danger w-100">
                                                                                <i class="fas fa-trash me-1"></i>حذف
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-4 border rounded bg-light">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p class="mb-0">لا توجد قيم مضافة لهذا الخيار</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5 border rounded bg-light">
                            <i class="fas fa-cogs fa-3x mb-3"></i>
                            <h5>لا توجد خيارات مضافة</h5>
                            <p class="mb-0">يمكنك إضافة خيارات للمنتج باستخدام الزر أعلاه</p>
                        </div>
                    @endif
                </div>

                <!-- الصورة -->
                <div class="section-card mb-4">
                    <div class="section-header bg-light-success rounded p-3 mb-3">
                        <h6 class="mb-0 text-success">
                            <i class="fas fa-image me-2"></i>صورة المنتج
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo" class="form-label fw-bold">تحميل صورة جديدة</label>
                                <div class="file-upload-container">
                                    <input wire:model="photo" class="form-control" type="file" id="photo"
                                        accept="image/*">
                                </div>
                                @error('photo')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="image-preview-container text-center">
                                <div class="current-image mb-3">
                                    <h6 class="text-muted mb-2">الصورة الحالية</h6>
                                    <img class="img-thumbnail shadow" src="{{ asset('uploads/' . $data->photo) }}"
                                        style="max-height: 200px; max-width: 100%;">
                                </div>

                                @if ($photo)
                                    <div class="new-image mt-3">
                                        <h6 class="text-success mb-2">معاينة الصورة الجديدة</h6>
                                        <img class="img-thumbnail shadow" src="{{ $photo->temporaryUrl() }}"
                                            style="max-height: 150px; max-width: 100%;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- رسائل التنبيه -->
                @if (session('done'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">تم التحديث بنجاح!</h6>
                                <p class="mb-0">تم تعديل المنتج بنجاح</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- أزرار التحكم -->
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary px-5 py-2">
                            <i class="fas fa-save me-2"></i>حفظ التعديلات
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary px-4 py-2">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('styles')
    <style>
        /* إضافة أنماط جديدة */
        .stock-info-card {
            transition: all 0.3s ease;
        }

        .stock-info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stock-stats {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            font-weight: 500;
            color: #6c757d;
        }

        .stat-value {
            font-size: 0.9rem;
        }

        /* بقية الأنماط تبقى كما هي */
        .section-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e3e6f0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .section-header {
            border-right: 4px solid;
        }

        .bg-light-primary {
            background-color: #e3f2fd !important;
            border-right-color: #2196f3;
        }

        .bg-light-warning {
            background-color: #fff3cd !important;
            border-right-color: #ffc107;
        }

        .bg-light-info {
            background-color: #d1ecf1 !important;
            border-right-color: #17a2b8;
        }

        .bg-light-success {
            background-color: #d1f2eb !important;
            border-right-color: #28a745;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #6610f2) !important;
        }

        .option-card {
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .option-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .value-card {
            transition: all 0.2s ease;
        }

        .value-card:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6 !important;
        }

        .custom-switch-container .card {
            transition: all 0.3s ease;
        }

        .custom-switch-container .card:hover {
            border-color: #28a745 !important;
            box-shadow: 0 2px 10px rgba(40, 167, 69, 0.2);
        }

        .image-preview-container {
            padding: 15px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
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

        .input-group-text {
            border-right: none;
            background: #f8f9fa !important;
        }

        .form-control {
            border-left: none;
            border-right: 1px solid #ced4da;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-badge .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }

        @media (max-width: 768px) {
            .section-card {
                padding: 1rem;
            }

            .card-header h5 {
                font-size: 1.1rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .stock-stats {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // تأثيرات بسيطة لتحسين التجربة
        document.addEventListener('DOMContentLoaded', function() {
            // تأثير للتبديل بين الحقول
            const switches = document.querySelectorAll('.form-check-input');
            switches.forEach(switchEl => {
                switchEl.addEventListener('change', function() {
                    this.parentElement.classList.toggle('active');
                });
            });

            // تأثير للبطاقات عند التمرير
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = "1";
                        entry.target.style.transform = "translateY(0)";
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.section-card').forEach(card => {
                card.style.opacity = "0";
                card.style.transform = "translateY(20px)";
                card.style.transition = "all 0.6s ease";
                observer.observe(card);
            });
        });
    </script>
@endsection
