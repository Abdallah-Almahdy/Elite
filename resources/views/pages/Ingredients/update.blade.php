@extends('admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4">تعديل المكون</h2>

    <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">اسم المكون</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $ingredient->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">الوحدة</label>
            <select name="unit_id" class="form-control" required>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ $ingredient->unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }} ({{ $unit->unit_code }})
                    </option>
                @endforeach
            </select>
            @error('unit_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">الكمية في المخزون</label>
            <input type="number" step="0.01" name="quantity_in_stock" class="form-control"
                   value="{{ old('quantity_in_stock', $ingredient->quantity_in_stock) }}">
            @error('quantity_in_stock') <small class="text-danger">{{ $message }}</small> @enderror
        </div>


        <div class="form-check mb-3">
            <select name="is_active" class="form-select">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>

        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
        
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
