@extends('admin.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column align-items-center justify-content-center mb-4">
        <h2 class="mb-4">المكونات</h2>
        <a href="{{ route('ingredients.create') }}" class="btn btn-success mb-3 w-100">إضافة مكون جديد</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الوحدة</th>
                <th>الكمية في المخزون</th>
                <th>الحالة</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ingredients as $ingredient)
                <tr>
                    <td>{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->unit->unit_code ?? '-' }}</td>
                    <td>{{ $ingredient->quantity_in_stock }}</td>
                    <td>
                        <div class="flex justify-center items-center">
                            @if (!$ingredient->is_active)
                                <span class="relative flex justify-center items-center size-3">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex size-3 rounded-full bg-red-500"></span>
                                </span>
                            @else
                                <span class="relative flex justify-center items-center size-3">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex size-3 rounded-full bg-green-500"></span>
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('ingredients.edit', $ingredient->id) }}">
                            <span class="btn btn-outline-info p-1">
                                ✏️
                            </span>
                        </a>
                        <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger p-1"
                                onclick="return confirm('هل أنت متأكد من حذف هذا المكون؟')">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">لا توجد مكونات</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
