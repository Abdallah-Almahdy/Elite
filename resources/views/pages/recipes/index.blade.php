@extends('admin.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column align-items-center justify-content-center mb-4" >

        <a href="{{ route('recipes.create') }}" class="btn btn-primary w-100">إضافة وصفة جديدة</a> <br>


    </div>

        <div class="d-flex flex-column align-items-center justify-content-center mb-4" style="background-color: #f8f9fa; padding: 2rem; border-radius: 8px;">

         <h2 class="mb-3">قائمة الوصفات</h2>


    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الوصفة</th>
                <th>المنتج</th>
                <th>المكونات والكميات</th>
                <th>الحاله التفعيل</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recipes as $index => $recipe)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $recipe->name }}</td>
                    <td>{{ $recipe->product->name ?? '-' }}</td>
                    <td>
                        @if($recipe->ingredients->count() > 0)
                            <ul class="mb-0">
                                @foreach($recipe->ingredients as $ingredient)
                                    <li>{{ $ingredient->name }} : {{ $ingredient->pivot->quantity_needed }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        <div class="flex justify-center items-center">
                            @if (!$recipe->is_active)
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
                        <!-- تعديل -->
                        <a href="{{ route('recipes.edit', $recipe->id) }}">
                            <span class="btn btn-outline-info p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </span>
                        </a>

                        <!-- حذف -->
                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger p-1"
                                onclick="return confirm('هل أنت متأكد من حذف هذه الوصفة؟')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد وصفات حتى الآن</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
