@extends('admin.app')

@section('content')
    {{-- {{ $users }} --}}
    <form method="GET" action="{{ route('statices.users') }}" class="mb-3 d-flex" style="max-width: 400px;">
        <input type="text" name="search" class="form-control me-2" placeholder="ابحث بالاسم أو الرقم"
            value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">بحث</button>
        @if (request('search'))
            <a href="{{ route('statices.users') }}" class="btn m-2 btn-secondary">
                إلغاء البحث
            </a>
        @endif
    </form>

    <table class="table table-striped   table-bordered  table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">الأسم</th>
                <th scope="col"> الرقم </th>
                <th scope="col">إعدادات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ str_replace(',', ' ', $user->name) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a type="button" href="{{ route('users.show', $user->id) }}"
                            class="btn btn-outline-info btn-sm me-1">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a type="button" href="{{ route('users.edit', $user->id) }}"
                            class="btn btn-outline-warning btn-sm me-1">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        {{-- <form action="{{ route('users.delete', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE') <!-- This tells Laravel to treat it as a DELETE request -->
                            <button href="{{ route('users.delete', $user->id) }}" type="button"
                                class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form> --}}

                        {{-- <a href="{{ route('users.delete', $user->id) }}" type="button"
                            class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> حذف
                        </a> --}}
                    </td>

                </tr>
            @empty
                <tr class="text-center text-danger">
                    <td colspan="4 ">
                        لا يوجد مستخدمين
                    </td>

                </tr>
            @endforelse

        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
@endsection
