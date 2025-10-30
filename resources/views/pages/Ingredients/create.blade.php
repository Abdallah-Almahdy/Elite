@extends('admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">إضافة مكون جديد</h2>

    <form action="{{ route('ingredients.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">اسم المكون</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
            <div class="mb-3">
            <label class="form-label">الكمية في المخزون</label>
            <input type="text" name="quantity_in_stock" class="form-control" value="{{ old('quantity_in_stock') }}" required>
            @error('quantity_in_stock') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">الوحدة</label>
            <select name="measurement_unit_id" class="form-select">
                <option value="">اختر الوحدة</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('measurement_unit_id', $ingredient->measurement_unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }} 
                    </option>
                @endforeach
            </select>
            @error('measurement_unit_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>


        {{-- <div class="mb-3">
            <label class="form-label">الكمية في المخزون</label>
            <input type="number" step="0.01" name="quantity_in_stock" class="form-control" value="{{ old('quantity_in_stock', 0) }}">
            @error('quantity_in_stock') <small class="text-danger">{{ $message }}</small> @enderror
            <option value="1">مفعل</option>
        </div> --}}

        <div class="form-check mb-3">
            <select name="is_active" class="form-select">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>

        </div>

        <button type="submit" class="btn btn-primary">حفظ</button>
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
