@extends('admin.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h5 class="text-center">تعديل الوحدة: {{ $unit->name }}</h5>
        </div>
        <form method="POST" action="{{ route('units.update', $unit->id) }}" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $unit->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="short_code">الرمز</label>
                            <input type="text" class="form-control" id="short_code" name="short_code"
                                value="{{ old('short_code', $unit->short_code) }}" required>
                            @error('short_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
<div class="col-sm-6">
    <div class="form-group">
        <label for="is_base_unit">نوع الوحدة</label>
        <select class="form-control" id="is_base_unit" name="is_base_unit"
            {{ $unit->derivedUnits()->count() > 0 ? 'disabled' : '' }}>
            <option value="0" {{ old('is_base_unit', $unit->is_base_unit) == 0 ? 'selected' : '' }}>وحدة مشتقة</option>
            <option value="1" {{ old('is_base_unit', $unit->is_base_unit) == 1 ? 'selected' : '' }}>وحدة أساسية</option>
        </select>

        {{-- Hidden input لإرسال القيمة عند التعطيل --}}
        @if ($unit->derivedUnits()->count() > 0)
            <input type="hidden" name="is_base_unit" value="{{ $unit->is_base_unit ? '1' : '0' }}">
            <small class="text-warning">لا يمكن تغيير نوع الوحدة لأنها مستخدمة كأساس لوحدات أخرى</small>
        @endif

        @error('is_base_unit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- نوع الوحدة --}}
<div class="col-sm-6">
    <div class="form-group">
        <label for="type">نوع الوحدة</label>
        <select class="form-control" id="type" name="type">
            <option value="">اختر النوع</option>
            <option value="weight" {{ old('type', $unit->type) == 'weight' ? 'selected' : '' }}>وزن</option>
            <option value="volume" {{ old('type', $unit->type) == 'volume' ? 'selected' : '' }}>حجم</option>
            <option value="count" {{ old('type', $unit->type) == 'count' ? 'selected' : '' }}>عدد</option>
        </select>
        @error('type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- رمز الوحدة للمشتقة فقط --}}
@if(! $unit->is_base_unit)
<div class="col-sm-6">
    <div class="form-group">
        <label for="unit_code">رمز الوحدة</label>
        <select class="form-control" id="unit_code" name="unit_code">
            <option value="">اختر رمز الوحدة</option>
            <option value="kg" {{ old('unit_code', $unit->unit_code) == 'kg' ? 'selected' : '' }}>كجم</option>
            <option value="g"  {{ old('unit_code', $unit->unit_code) == 'g'  ? 'selected' : '' }}>جرام</option>
            <option value="mg" {{ old('unit_code', $unit->unit_code) == 'mg' ? 'selected' : '' }}>مليجرام</option>
            <option value="ton"{{ old('unit_code', $unit->unit_code) == 'ton'? 'selected' : '' }}>طن</option>
            <option value="l"  {{ old('unit_code', $unit->unit_code) == 'l'  ? 'selected' : '' }}>لتر</option>
            <option value="ml" {{ old('unit_code', $unit->unit_code) == 'ml' ? 'selected' : '' }}>مليلتر</option>
            <option value="piece" {{ old('unit_code', $unit->unit_code) == 'piece' ? 'selected' : '' }}>قطعة</option>
        </select>
        @error('unit_code')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
@endif


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="conversion_factor">معامل التحويل</label>
                            <input type="number" step="0.0001" class="form-control" id="conversion_factor"
                                name="conversion_factor" value="{{ old('conversion_factor', $unit->conversion_factor) }}"
                                required>
                            @error('conversion_factor')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row" id="base_unit_field">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="base_unit_id">الوحدة الأساسية</label>
                            <select class="form-control" id="base_unit_id" name="base_unit_id"
                                {{ $unit->is_base_unit ? 'disabled' : '' }}>
                                <option value="">اختر الوحدة الأساسية</option>
                                @foreach ($baseUnits as $baseUnit)
                                    <option value="{{ $baseUnit->id }}"
                                        {{ old('base_unit_id', $unit->base_unit_id) == $baseUnit->id ? 'selected' : '' }}>
                                        {{ $baseUnit->name }} ({{ $baseUnit->short_code }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($unit->is_base_unit)
                                <input type="hidden" name="base_unit_id" value="">
                            @endif
                            @error('base_unit_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">حالة التفعيل</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1" {{ old('active', $unit->active) == 1 ? 'selected' : '' }}>مفعل
                                </option>
                                <option value="0" {{ old('active', $unit->active) == 0 ? 'selected' : '' }}>غير مفعل
                                </option>
                            </select>
                            @error('active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isBaseUnitSelect = document.getElementById('is_base_unit');
            const baseUnitField = document.getElementById('base_unit_field');
            const conversionFactorField = document.getElementById('conversion_factor');

            function toggleBaseUnitField() {
                if (isBaseUnitSelect.value == '1') {
                    baseUnitField.style.display = 'none';
                    conversionFactorField.value = 1;
                    conversionFactorField.readOnly = true;
                } else {
                    baseUnitField.style.display = 'block';
                    conversionFactorField.readOnly = false;
                }
            }

            // Initial state
            toggleBaseUnitField();

            // Change event
            isBaseUnitSelect.addEventListener('change', toggleBaseUnitField);
        });
    </script>
@endsection
