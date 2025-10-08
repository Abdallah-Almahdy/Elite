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

    <a href="{{ route('representatives.create') }}"
        class="btn btn-outline-success w-100 mb-2 text-center d-flex align-items-center justify-content-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        إضافة مندوب جديد
    </a>

    <table class="table table-hover table-bordered text-center text-nowrap">
        <thead class="bg-blue-200">
            <tr>
                <th scope="col">الاسم</th>
                <th scope="col">نسبة العمولة</th>
                <th scope="col">الهاتف</th>
                <th scope="col">البريد الإلكتروني</th>
                <th scope="col">حالة التفعيل</th>
                <th scope="col">خيارات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($representatives as $representative)
                <tr>
                    <td>{{ $representative->name }}</td>
                    <td>{{ $representative->commission_rate }}%</td>
                    <td>{{ $representative->phone ?? '--' }}</td>
                    <td>{{ $representative->email ?? '--' }}</td>
                    <td>
                        <div class="flex justify-center items-center">
                            @if (!$representative->active)
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
                        <a href="{{ route('representatives.edit', $representative->id) }}">
                            <span class="btn btn-outline-info p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center bg-danger">لا يوجد مناديب</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
