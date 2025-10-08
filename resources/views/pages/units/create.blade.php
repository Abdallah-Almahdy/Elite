@extends('admin.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h5 class="text-center">إضافة وحدة جديدة</h5>
        </div>
        <form method="POST" action="{{ route('units.store') }}" role="form">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- الاسم --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- الرمز --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="short_code">الرمز</label>
                            <input type="text" class="form-control" id="short_code" name="short_code"
                                   value="{{ old('short_code') }}" required>
                            @error('short_code') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- نوع الوحدة (أساسية / مشتقة) --}}
                <div class="form-group">
                    <label for="is_base_unit">نوع الوحدة</label>
                    <select class="form-control" id="is_base_unit" name="is_base_unit" required>
                        <option value="1" {{ old('is_base_unit') == 1 ? 'selected' : '' }}>وحدة أساسية</option>
                        <option value="0" {{ old('is_base_unit') == 0 ? 'selected' : '' }}>وحدة مشتقة</option>
                    </select>
                    @error('is_base_unit') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- النوع --}}
                <div class="form-group">
                    <label for="type">النوع</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">اختر النوع</option>
                        <option value="weight" {{ old('type') == 'weight' ? 'selected' : '' }}>وزن</option>
                        <option value="volume" {{ old('type') == 'volume' ? 'selected' : '' }}>حجم</option>
                        <option value="count" {{ old('type') == 'count' ? 'selected' : '' }}>قطعة</option>
                    </select>
                    @error('type') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- حقول المشتقة --}}
                <div id="derived_fields">
                    <div class="row">
                        {{-- الوحدة الأساسية --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="base_unit_id">الوحدة الأساسية</label>
                                <select class="form-control" id="base_unit_id" name="base_unit_id">
                                    <option value="">اختر الوحدة الأساسية</option>
                                    @foreach ($baseUnits as $baseUnit)
                                        <option value="{{ $baseUnit->id }}"
                                            {{ old('base_unit_id') == $baseUnit->id ? 'selected' : '' }}>
                                            {{ $baseUnit->name }} ({{ $baseUnit->short_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('base_unit_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- معامل التحويل --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="conversion_factor">معامل التحويل</label>
                                <input type="number" step="0.0001" class="form-control" id="conversion_factor"
                                       name="conversion_factor" value="{{ old('conversion_factor', 1) }}">
                                @error('conversion_factor') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- رمز الوحدة (للمشتقة فقط) --}}
                    <div class="form-group">
                        <label for="unit_code">رمز الوحدة</label>
                        <select name="unit_code" id="unit_code" class="form-control">
                            <option value="">اختر الوحدة</option>
                            <option value="kg" {{ old('unit_code') == 'kg' ? 'selected' : '' }}>كيلو</option>
                            <option value="g" {{ old('unit_code') == 'g' ? 'selected' : '' }}>جرام</option>
                            <option value="mg" {{ old('unit_code') == 'mg' ? 'selected' : '' }}>مليجرام</option>
                            <option value="ton" {{ old('unit_code') == 'ton' ? 'selected' : '' }}>طن</option>
                            <option value="l" {{ old('unit_code') == 'l' ? 'selected' : '' }}>لتر</option>
                            <option value="ml" {{ old('unit_code') == 'ml' ? 'selected' : '' }}>مليلتر</option>
                            <option value="piece" {{ old('unit_code') == 'piece' ? 'selected' : '' }}>قطعة</option>
                        </select>
                        @error('unit_code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- الحالة --}}
                <div class="form-group">
                    <label for="active">الحالة</label>
                    <select class="form-control" id="active" name="active">
                        <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>مفعل</option>
                        <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>غير مفعل</option>
                    </select>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isBaseUnitSelect = document.getElementById('is_base_unit');
            const derivedFields = document.getElementById('derived_fields');
            const conversionFactor = document.getElementById('conversion_factor');
            const unitCode = document.getElementById('unit_code');

            function toggleFields() {
                if (isBaseUnitSelect.value === '1') {
                    // وحدة أساسية
                    derivedFields.style.display = 'none';
                    conversionFactor.value = 1;
                    unitCode.value = '';
                } else {
                    // وحدة مشتقة
                    derivedFields.style.display = 'block';
                }
            }

            toggleFields();
            isBaseUnitSelect.addEventListener('change', toggleFields);
        });
    </script>
@endsection
