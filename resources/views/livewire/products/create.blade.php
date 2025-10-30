<div class="card card-primary">
    <div class="card-header">
        <h5 class="text-center mb-0">إضافة صنف جديد</h5>
    </div>

    <form wire:submit="create" role="form">
        <div class="card-body">
            <!-- الصف الأول: الاسم والسعر -->

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control" id="name"
                            placeholder="ادخل الاسم">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description" class="form-label">الوصف</label>
                        <input wire:model="description" type="text" class="form-control" id="description"
                            placeholder="ادخل الوصف">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="section" class="form-label">القسم <span class="text-danger">*</span></label>
                        <select wire:model="section" id="section" class="form-control">
                            <option value="" class="text-muted">اختر القسم...</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div class="text-danger small mt-1">الرجاء اختيار القسم</div>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check mt-3">
                        <input type="checkbox" wire:model.live="hasRecipe" id="hasRecipe" class="form-check-input">
                        <label for="isComposite" class="form-check-label">منتج مركب</label>
                    </div>
                </div>
            </div>
            {{-- اضافه الوحدات --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info ">
                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                <span> الوحدات</span>
                                <button type="button" wire:click="addUnit" class="btn btn-sm btn-light">
                                    <i class="fa fa-plus me-1"></i>إضافة وحدة
                                </button>
                            </h6>
                        </div>

                        <div class="card-body">
                            @foreach ($units as $index => $unit)
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-5">
                                        <label class="form-label">اسم الوحدة</label>
                                        <input type="text" wire:model="units.{{ $index }}.name"
                                            class="form-control" placeholder="مثل: كيلو، لتر..." required>
                                        @error('units.' . $index . '.name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">السعر</label>
                                        <input type="number" wire:model="units.{{ $index }}.price"
                                            class="form-control">
                                    </div>

                                    @if (!$hasRecipe)
                                        <div class="col-md-2">
                                            <label class="form-label">معامل التحويل</label>
                                            <input type="number"
                                                wire:model="units.{{ $index }}.conversion_factor"
                                                class="form-control" step="0.001">
                                        </div>
                                    @endif

                                    <div class="col-md-3">
                                        <label class="form-label">Barcodes</label>
                                        @foreach ($unit['bar_codes'] as $bIndex => $barcode)
                                            <div class="input-group mb-1">
                                                <input type="text"
                                                    wire:model="units.{{ $index }}.bar_codes.{{ $bIndex }}"
                                                    class="form-control" placeholder="barcode">
                                                <button type="button"
                                                    wire:click="removeBarCode({{ $index }}, {{ $bIndex }})"
                                                    class="btn btn-danger btn-sm">×</button>
                                            </div>
                                        @endforeach
                                        <button type="button" wire:click="addBarCode({{ $index }})"
                                            class="btn btn-sm btn-light">إضافة barcode</button>
                                    </div>

                                    @if ($hasRecipe)
    <div class="col-12 mt-2">
        <div class="card card-light">
            <div class="card-header">الوصفة</div>
            <div class="card-body">
                @php
                    $components = $unit['components'] ?? [];
                @endphp

                @if (count($components) > 0)
                    @foreach ($components as $cIndex => $comp)
                        <div class="row mb-2 align-items-center">
                            <div class="col-md-4">
                                <select wire:model="units.{{ $index }}.components.{{ $cIndex }}.product_id"
                                    class="form-select">
                                    <option value="">اختر المنتج</option>
                                    @foreach ($allProducts as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number"
                                    wire:model="units.{{ $index }}.components.{{ $cIndex }}.quantity"
                                    class="form-control" placeholder="الكمية">
                            </div>
                            <div class="col-md-3">
                                <select wire:model="units.{{ $index }}.components.{{ $cIndex }}.unit_id"
                                    class="form-select">
                                    <option value="">اختر الوحدة</option>
                                    @foreach ($allProducts as $p)
                                        @foreach ($p->units ?? [] as $uIndex => $u)
                                            <option value="{{ $u['id'] ?? $uIndex }}">{{ $u['name'] }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button"
                                    wire:click="removeComponent({{ $index }}, {{ $cIndex }})"
                                    class="btn btn-danger btn-sm">×</button>
                            </div>
                        </div>
                    @endforeach
                @endif

                <button type="button" wire:click="addComponent({{ $index }})"
                    class="btn btn-sm btn-light">إضافة مكون</button>
            </div>
        </div>
    </div>
@endif


                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>



            <!-- قسم الـ Options والـ Values المضاف -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                <span>خيارات المنتج</span>
                                <button type="button" wire:click="addOption" class="btn btn-sm btn-light">
                                    <i class="fa fa-plus me-1"></i>إضافة خيار
                                </button>
                            </h6>
                        </div>


                        <div class="card-body">
                            @if (count($options) > 0)
                                @foreach ($options as $optionIndex => $option)
                                    <div class="card mb-3 border-secondary">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">خيار {{ $loop->iteration }}</h6>
                                            <button type="button" wire:click="removeOption({{ $optionIndex }})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">اسم الخيار</label>
                                                        <input type="text"
                                                            wire:model="options.{{ $optionIndex }}.name"
                                                            class="form-control"
                                                            placeholder="مثل: المقاس، اللون، الخ..." required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-check mt-4">
                                                            <input type="checkbox"
                                                                wire:model="options.{{ $optionIndex }}.active"
                                                                class="form-check-input" checked>
                                                            <label class="form-check-label">مفعل</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- قيم الخيار -->
                                            <div class="values-section">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">قيم الخيار</h6>
                                                    <button type="button" wire:click="addValue({{ $optionIndex }})"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-plus me-1"></i>إضافة قيمة
                                                    </button>
                                                </div>

                                                @if (isset($option['values']) && count($option['values']) > 0)
                                                    @foreach ($option['values'] as $valueIndex => $value)
                                                        <div class="card mb-2 border-light">
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">اسم
                                                                                القيمة</label>
                                                                            <input type="text"
                                                                                wire:model="options.{{ $optionIndex }}.values.{{ $valueIndex }}.name"
                                                                                class="form-control"
                                                                                placeholder="مثل: كبير، أحمر، الخ..."
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">السعر
                                                                                الإضافي</label>
                                                                            <input type="number"
                                                                                wire:model="options.{{ $optionIndex }}.values.{{ $valueIndex }}.price"
                                                                                class="form-control"
                                                                                placeholder="0.00" step="0.01"
                                                                                value="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">الإجراءات</label>
                                                                            <button type="button"
                                                                                wire:click="removeValue({{ $optionIndex }}, {{ $valueIndex }})"
                                                                                class="btn btn-sm btn-outline-danger w-100">
                                                                                <i class="fa fa-times me-1"></i>حذف
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        لا توجد قيم مضافة لهذا الخيار
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    لا توجد خيارات مضافة حالياً
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- الصف الرابع: يحتوي على وصفة والصورة -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check mt-3">
                        <input type="checkbox" wire:model="hasRecipe" id="hasRecipe" class="form-check-input">
                        <label for="hasRecipe" class="form-check-label">يحتوي على وصفة</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="photo" class="form-label">الصورة</label>
                        <input wire:model="photo" class="form-control" type="file" id="photo"
                            accept="image/*">
                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        @if ($photo)
                            <div class="mt-2">
                                <img class="img-thumbnail" src="{{ $photo->temporaryUrl() }}"
                                    style="max-height: 150px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- رسالة النجاح -->
            @if (session('done'))
                <div class="alert alert-success d-flex align-items-center mt-3" role="alert">
                    <i class="fa fa-check-circle me-2" aria-hidden="true"></i>
                    <div>تم اضافة منتج جديد بنجاح</div>
                </div>
            @endif
        </div>

        <!-- زر الإرسال -->
        <div class="card-footer">
            <button type="submit" id="done" class="btn btn-primary px-4">اضافة</button>
        </div>
    </form>
</div>

<script>
    // تفعيل/تعطيل حقل الكمية بناءً على حالة المخزون
    document.addEventListener('DOMContentLoaded', function() {
        const stockToggle = document.getElementById('stock');
        const stockQntDiv = document.getElementById('stockQntDiv');

        if (stockToggle && stockQntDiv) {
            stockToggle.addEventListener('change', function() {
                if (this.checked) {
                    stockQntDiv.style.display = 'block';
                } else {
                    stockQntDiv.style.display = 'none';
                }
            });
        }
    });
</script>
