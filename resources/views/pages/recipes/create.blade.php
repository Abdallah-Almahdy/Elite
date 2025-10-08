@extends('admin.app')

@section('content')
<div class="container">
    <h2>إضافة وصفة جديدة</h2>

    <form action="{{ route('recipes.store') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label>اسم الوصفة</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <label class="w-100">
                    <input type="checkbox" id="is_base" name="is_base" value="1" {{ old('is_base') ? 'checked' : '' }}>
                    هل هذه وصفة أساسية (بدون ربط بمنتج)؟
                </label>
            </div>
        </div>

        {{-- اختيار المنتج (يظهر فقط إذا الوصفة مش أساسية) --}}
        <div class="mb-3" id="product_section">
            <label>المنتج المرتبط</label>
            <select name="product_id" class="form-control">
                <option value="">اختر المنتج</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Checkbox للوصفة الأساسية --}}
        {{-- <div class="mb-3">
            <label>
                <input type="checkbox" id="is_base" name="is_base" value="1" {{ old('is_base') ? 'checked' : '' }}>
                هل هذه وصفة أساسية (بدون ربط بمنتج)؟
            </label>
        </div> --}}

        <div class="mb-3">
            <label>الوصفة الأساسية (اختياري)</label>
            <select name="base_recipe_id" class="form-control">
                <option value="">لا شيء</option>
                @foreach($baseRecipes as $baseRecipe)
                    <option value="{{ $baseRecipe->id }}">{{ $baseRecipe->name }}</option>
                @endforeach
            </select>
        </div>

        <hr>
        <h4>المكونات</h4>
        <div id="ingredients-container">
            <div class="ingredient-row row mb-2">
                <div class="col-md-5">
                    <select name="ingredients[0][id]" class="form-control" required>
                        <option value="">اختر المكون</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="ingredients[0][quantity_needed]" class="form-control" placeholder="الكمية" step="0.01" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-ingredient">X</button>
                </div>
            </div>
        </div>

        <div class="form-check mb-3">
            <select name="is_active" class="form-select">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>

        </div>

        <button type="button" id="add-ingredient" class="btn btn-primary mb-3">إضافة مكون</button>
        <button type="submit" class="btn btn-success">حفظ الوصفة</button>
    </form>
</div>

<script>
    // إخفاء/إظهار اختيار المنتج بناءً على الوصفة الأساسية
    document.addEventListener("DOMContentLoaded", function () {
        let isBaseCheckbox = document.getElementById("is_base");
        let productSection = document.getElementById("product_section");

        function toggleProductSection() {
            productSection.style.display = isBaseCheckbox.checked ? "none" : "block";
        }

        toggleProductSection();
        isBaseCheckbox.addEventListener("change", toggleProductSection);
    });

    // إضافة وحذف المكونات
    let ingredientIndex = 1;

    document.getElementById('add-ingredient').addEventListener('click', function () {
        let container = document.getElementById('ingredients-container');
        let newRow = container.querySelector('.ingredient-row').cloneNode(true);

        // إعادة تعيين القيم
        newRow.querySelectorAll('input, select').forEach(function(el) {
            if(el.name.includes('ingredients')){
                el.name = `ingredients[${ingredientIndex}]${el.name.substring(el.name.indexOf(']')+1)}`;
            }
            el.value = '';
        });

        container.appendChild(newRow);
        ingredientIndex++;

        // زر إزالة
        newRow.querySelector('.remove-ingredient').addEventListener('click', function () {
            newRow.remove();
        });
    });

    // زر إزالة لأول عنصر
    document.querySelectorAll('.remove-ingredient').forEach(btn => {
        btn.addEventListener('click', function () {
            btn.closest('.ingredient-row').remove();
        });
    });
</script>
@endsection
