<div class="card card-primary h-100 d-flex flex-column">
    {{-- All Validation Errors at top --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-header bg-primary text-white py-3">
        <h5 class="text-center mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>إضافة صنف جديد</h5>
    </div>

    <form wire:submit="create" role="form" class="flex-grow-1 d-flex flex-column">
        <!-- قسم المحتوى القابل للتمرير -->
        <div class="card-body flex-grow-1" style="overflow-y: auto; max-height: calc(100vh - 200px);">

            <!-- الصف الأول: الاسم والوصف والقسم -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" class="form-label fw-semibold">الاسم <span
                                class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control " id="name"
                            placeholder="ادخل الاسم">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description" class="form-label fw-semibold">الوصف</label>
                        <input wire:model="description" type="text" class="form-control " id="description"
                            placeholder="ادخل الوصف">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="section" class="form-label fw-semibold">
                            القسم <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <select wire:model="section" id="section" class="form-select">
                                <option value="" class="text-muted">اختر القسم...</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>

                            <!-- زر الإضافة -->
                            <a href="{{ route('sections.create') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>

                        @error('section_id')
                            <div class="text-danger small mt-1">الرجاء اختيار القسم</div>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- الصف الثاني: المنتج المركب والصورة وإضافة قسم -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-check mt-3">
                        <input type="checkbox" wire:model.live="hasRecipe" id="hasRecipe" class="form-check-input">
                        <label for="hasRecipe" class="form-check-label fw-semibold">منتج مركب</label>
                    </div>
                    <div class="form-check mt-3">
                        <input type="checkbox" wire:model.live="isActive" id="isActive" class="form-check-input">
                        <label for="isActive" class="form-check-label fw-semibold">مفعل</label>
                    </div>
          
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="photo" class="form-label fw-semibold">الصورة</label>
                        <input wire:model="photo" class="form-control" type="file" id="photo" accept="image/*">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="company" class="form-label fw-semibold">
                            الشركة التابع لها المنتج <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <select wire:model="company" id="company" class="form-select">
                                <option value="" class="text-muted">اختر الشركة...</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>

                            <!-- زر الإضافة -->
                            <a href="{{ route('companies.create') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم الوحدات -->
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-balance-scale me-2"></i>الوحدات</h6>
                    <button type="button" wire:click="addUnit" class="btn btn-sm btn-light">
                        <i class="fa fa-plus me-1"></i>إضافة وحدة
                    </button>
                </div>

                <div class="card-body">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم الوحدة</th>
                                @if (!$hasRecipe)
                                    <th>معامل التحويل</th>
                                @endif
                                <th>سعر البيع</th>
                                <th> سعر الشرا</th>
                                <th>الباركود</th>
                                @if ($hasRecipe)
                                    <th>إجمالي التكلفة</th>
                                @endif
                                <th>خيارات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($units as $index => $unit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <!-- اسم الوحدة -->
                                    <td>
                                        <div class="input-group">
                                            <select wire:model="units.{{ $index }}.measure_unit_id"
                                                class="form-select" required>
                                                <option value="">اختر الوحدة</option>
                                                @foreach ($MeasureUnits as $measure)
                                                    <option value="{{ $measure->id }}">{{ $measure->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-success btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#addUnitModal">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        @error('units.' . $index . '.measure_unit_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </td>

                                    <!-- معامل التحويل -->
                                    @if (!$hasRecipe)
                                        <td>
                                            <input type="text" step="0.001"
                                                wire:model.lazy="units.{{ $index }}.conversion_factor"
                                                class="form-control text-center"
                                                @if ($index == 0) disabled @endif>
                                        </td>
                                    @endif

                                    <!-- السعر -->
                                    <td>
                                        <input type="number" wire:model.lazy="units.{{ $index }}.sallPrice"
                                            class="form-control text-center">
                                    </td>

                                    <!-- سعر البيع -->
                                    <td>
                                        <input type="number" wire:model.lazy="units.{{ $index }}.price"
                                            class="form-control text-center">
                                    </td>

                                    <!-- الباركود -->
                                    <td>
                                        @foreach ($unit['bar_codes'] as $bIndex => $barcode)
                                            <div class="input-group mb-2">

                                                <input type="text"
                                                    wire:model="units.{{ $index }}.bar_codes.{{ $bIndex }}"
                                                    class="form-control text-center" placeholder="barcode">

                                                @if ($bIndex != 0)
                                                    <button type="button"
                                                        wire:click="removeBarCode({{ $index }}, {{ $bIndex }})"
                                                        class="btn btn-danger btn-sm"><i
                                                            class="fa fa-times"></i></button>
                                                @endif
                                                @if ($bIndex == 0)
                                                    <button type="button"
                                                        wire:click="addBarCode({{ $index }})"
                                                        class="btn btn-success btn-sm"><i
                                                            class="fa fa-plus"></i></button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>

                                    <!-- إجمالي التكلفة -->
                                    @if ($hasRecipe)
                                        <td>
                                            <div class="p-2 bg-light rounded">
                                                <span class="fw-bold text-primary">
                                                    {{ number_format($unit['total_cost'] ?? 0, 2) }} ج.م
                                                </span>
                                            </div>
                                        </td>
                                    @endif


                                    <!-- خيارات -->
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if ($loop->index > 0)
                                                <button type="button" wire:click="removeUnit({{ $index }})"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif

                                            @if ($hasRecipe)
                                                <button type="button"
                                                    wire:click="toggleRecipeVisibility({{ $index }})"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    @if (!empty($showRecipes[$index]) && $showRecipes[$index])
                                                        <i class="fa fa-chevron-up"></i>
                                                    @else
                                                        <i class="fa fa-chevron-down"></i>
                                                    @endif
                                                </button>
                                            @endif
                                        </div>
                                    </td>


                                </tr>

                                <!-- جدول الوصفة الداخلي -->
                                @if ($hasRecipe && !empty($showRecipes[$index]) && $showRecipes[$index])
                                    <tr class="bg-light">
                                        <td colspan="8">
                                            <div class="border rounded p-3 mt-2">
                                                <h6 class="fw-semibold text-secondary mb-3">
                                                    <i class="fa fa-utensils me-1"></i>الوصفة
                                                </h6>

                                                <table
                                                    class="table table-sm table-bordered align-middle text-center mb-0">
                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th>المنتج</th>
                                                            <th>الوحدة</th>
                                                            <th>الكمية</th>
                                                            <th>التحكم</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $components = $unit['components'] ?? [];
                                                        @endphp

                                                        @foreach ($components as $cIndex => $comp)
                                                            <tr>
                                                                <!-- البحث عن المنتج -->
                                                                <td class="position-relative">
                                                                    @if (empty($unit['components'][$cIndex]['product_id']))
                                                                        <input type="text"
                                                                            wire:model.live="units.{{ $index }}.components.{{ $cIndex }}.search"
                                                                            class="form-control form-control-sm"
                                                                            placeholder="ابحث عن منتج...">

                                                                        @if (!empty($unit['components'][$cIndex]['search']) && isset($unit['components'][$cIndex]['results']))
                                                                            <ul class="list-group position-absolute w-100"
                                                                                style="z-index: 999;">
                                                                                @forelse ($unit['components'][$cIndex]['results'] as $result)
                                                                                    <li class="list-group-item list-group-item-action"
                                                                                        wire:click="selectProduct({{ $index }}, {{ $cIndex }}, {{ $result['id'] }})">
                                                                                        {{ $result['name'] }}
                                                                                    </li>
                                                                                @empty
                                                                                    <li
                                                                                        class="list-group-item text-muted">
                                                                                        لا توجد نتائج</li>
                                                                                @endforelse
                                                                            </ul>
                                                                        @endif
                                                                    @else
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center bg-white p-1 rounded">
                                                                            <span class="text-success small">
                                                                                ✅
                                                                                {{ $unit['components'][$cIndex]['product_name'] }}
                                                                            </span>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-outline-danger"
                                                                                wire:click="clearProductSelection({{ $index }}, {{ $cIndex }})">
                                                                                ❌
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <select
                                                                        wire:model.live="units.{{ $index }}.components.{{ $cIndex }}.component_unit_id"
                                                                        class="form-select form-select-sm">
                                                                        <option value="">اختر الوحدة</option>
                                                                        @foreach ($units[$index]['components'][$cIndex]['available_units'] ?? [] as $u)
                                                                            <option value="{{ $u['id'] }}">
                                                                                {{ $u['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <!-- الكمية -->
                                                                <td>
                                                                    <input type="number"
                                                                        wire:model.live="units.{{ $index }}.components.{{ $cIndex }}.quantity"
                                                                        class="form-control form-control-sm text-center"
                                                                        placeholder="الكمية">
                                                                </td>

                                                                <!-- الوحدة -->


                                                                <!-- التحكم -->
                                                                <td>
                                                                    <button type="button"
                                                                        wire:click="removeComponent({{ $index }}, {{ $cIndex }})"
                                                                        class="btn btn-danger btn-sm">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td colspan="4" class="text-start">
                                                                <button type="button"
                                                                    wire:click="addComponent({{ $index }})"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="fa fa-plus me-1"></i>إضافة مكون
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif


                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- قسم الـ Options والـ Values -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-primary">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-cogs me-2"></i>خيارات المنتج</h6>
                            <button type="button" wire:click="addOption" class="btn btn-sm btn-light">
                                <i class="fa fa-plus me-1"></i>إضافة خيار
                            </button>
                        </div>

                        <div class="card-body">
                            @if (count($options) > 0)
                                @foreach ($options as $optionIndex => $option)
                                    <div class="card mb-3 border-secondary">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">خيار {{ $loop->iteration }}</h6>
                                            <button type="button" wire:click="removeOption({{ $optionIndex }})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fa fa-times me-1"></i>حذف
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label fw-semibold">اسم الخيار</label>
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
                                                            <label class="form-check-label fw-semibold">مفعل</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- قيم الخيار -->
                                            <div class="values-section">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0 fw-semibold">قيم الخيار</h6>
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
                                                                            <label class="form-label fw-semibold">اسم
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
                                                                            <label class="form-label fw-semibold">السعر
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
                                                                            <label
                                                                                class="form-label d-block">&nbsp;</label>
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
                                                        <i class="fas fa-info-circle me-1"></i>لا توجد قيم مضافة
                                                        لهذا
                                                        الخيار
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle me-1"></i>لا توجد خيارات مضافة حالياً
                                </div>
                            @endif
                        </div>
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
        <div class="card-footer bg-light py-3">
            <button type="submit" id="done" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-save me-2"></i>اضافة المنتج
            </button>
        </div>
    </form>

    <!-- Modal لإضافة وحدة -->
    <div wire:ignore.self class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="addUnitModalLabel">إضافة وحدة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeUnit">
                        <div class="form-group mb-3">
                            <label class="form-label">اسم الوحدة</label>
                            <input type="text" wire:model="newUnit.name" class="form-control"
                                placeholder="مثل: كيلو، لتر...">
                            @error('newUnit.name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" wire:model="newUnit.is_active" class="form-check-input"
                                id="isActive">
                            <label class="form-check-label" for="isActive">نشطة</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // استماع للـ event من Livewire
            Livewire.on('prices-updated', (data) => {
                if (!data.units) return;

                data.units.forEach((unit, index) => {
                    // نجيب input سعر الشراء
                    const priceInput = document.querySelector(
                        `[wire\\:model\\.lazy="units\\.${index}\\.price"]`);
                    // نجيب input سعر البيع
                    const sellInput = document.querySelector(
                        `[wire\\:model\\.lazy="units\\.${index}\\.sallPrice"]`);

                    if (priceInput) {
                        // نحدّث القيمة يدوياً
                        priceInput.value = unit.price;
                        // نرسل حدث input عشان Livewire يعرف التغيير
                        priceInput.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }

                    if (sellInput) {
                        sellInput.value = unit.sallPrice;
                        sellInput.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                });
            });
        });
    </script>
@endpush
