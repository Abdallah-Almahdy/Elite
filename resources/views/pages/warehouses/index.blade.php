@extends('admin.app')

@section('content')
    @if (session('success'))
        <div class="callout bg-success callout-success">
            <h5 class="flex-row flex">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p>{{ session('success') }}</p>
            </h5>
        </div>
    @endif

    @if (session('error'))
        <div class="callout bg-danger callout-danger">
            <h5 class="flex-row flex">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <p>{{ session('error') }}</p>
            </h5>
        </div>
    @endif

    <div class="flex m-2 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('warehouses.index') }}"
            class="inline-flex bg-blue-500 text-white items-center h-12 px-2 py-2 text-center text-gray-700 border border-b-0 border-gray-300 sm:px-4 dark:border-gray-500 rounded-t-md -px-1 dark:text-white whitespace-nowrap focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6 ml-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>


            <span class="mx-1 text-sm sm:text-base">
                المخازن
            </span>
        </a>

        <a href="{{ route('warehouses_trasactions.index') }}"
            class="inline-flex items-center h-12 px-2 py-2 text-center text-gray-700 bg-transparent  border-b border-gray-300 sm:px-4 dark:border-gray-500 -px-1 dark:text-white whitespace-nowrap cursor-base focus:outline-none hover:border-gray-400 dark:hover:border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>


            <span class="mx-1 text-sm sm:text-base">
                الحركات المخزنية
            </span>
        </a>

    </div>

    <a href="{{ route('warehouses.create') }}"
        class="btn btn-outline-success w-100 mb-2 text-center d-flex align-items-center justify-content-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        إضافة مخزن جديد
    </a>

    <table class="table table-hover table-bordered text-center text-nowrap">
        <thead class="bg-blue-200">
            <tr>
                <th scope="col">الاسم</th>
                <th scope="col">الكود</th>
                <th scope="col">العنوان</th>
                <th scope="col">الهاتف</th>
                <th scope="col">البريد الإلكتروني</th>
                <th scope="col">الحالة</th>
                <th scope="col">خيارات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($warehouses as $warehouse)
                <tr>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->code }}</td>
                    <td>
                        @if ($warehouse->primaryAddress())
                            {{ $warehouse->primaryAddress()->city }}, {{ $warehouse->primaryAddress()->line1 }}
                        @else
                            {{ $warehouse->address }}
                        @endif
                    </td>
                    <td>
                        @if ($warehouse->primaryPhone())
                            {{ $warehouse->primaryPhone()->number }}
                        @else
                            {{ $warehouse->phone }}
                        @endif
                    </td>
                    <td>{{ $warehouse->email }}</td>
                    <td>
                        <div class="flex justify-center items-center">
                            @if (!$warehouse->is_active)
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
                        <a href="{{ route('warehouses.show', $warehouse->id) }}">
                            <span class="btn btn-outline-info p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                        </a>
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}">
                            <span class="btn btn-outline-info p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </span>
                        </a>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger p-1"
                                onclick="return confirm('هل أنت متأكد من حذف هذا المستودع؟')">
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
                    <td colspan="7" class="text-center bg-danger">لا توجد مستودعات</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
