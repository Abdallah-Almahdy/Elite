@extends('admin.app')

@section('content')
<div class="container">
    <h2>تفاصيل الوصفة</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h4>{{ $recipe->name }}</h4>
            <p><strong>الحالة:</strong> {{ $recipe->is_active ? 'مفعل' : 'غير مفعل' }}</p>
            <p><strong>نوع:</strong> {{ $recipe->is_base ? 'وصفة أساسية' : 'مرتبطة بمنتج' }}</p>

            @if($recipe->product)
                <p><strong>المنتج المرتبط:</strong> {{ $recipe->product->name }}</p>
            @endif

            @if($recipe->baseRecipe)
                <p><strong>الوصفة الأساسية:</strong> {{ $recipe->baseRecipe->name }}</p>
            @endif
        </div>
    </div>

    <h5>المكونات:</h5>
    @if($recipe->ingredients->count() > 0)
        <ul class="list-group mb-3">
            @foreach($recipe->ingredients as $ingredient)
                <li class="list-group-item">
                    {{ $ingredient->name }} - {{ $ingredient->pivot->quantity_needed }}
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">لا يوجد مكونات.</p>
    @endif

    <h5>الوصفات التابعة:</h5>
    @if($recipe->childRecipes->count() > 0)
        <ul class="list-group mb-3">
            @foreach($recipe->childRecipes as $child)
                <li class="list-group-item">
                    <a href="{{ route('recipes.show', $child->id) }}">
                        {{ $child->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">لا يوجد وصفات تابعة.</p>
    @endif

    <a href="{{ route('recipes.index') }}" class="btn btn-secondary">رجوع للقائمة</a>
    <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-primary">تعديل</a>
</div>
@endsection
