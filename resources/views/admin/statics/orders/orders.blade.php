@extends('admin.app')

@section('content')
    <div class="flex justify-between ">

        <form method="GET" action="{{ route('statices.orders') }}" class="mb-3 d-flex" style="max-width: 400px;">
            <input type="text" name="search" class="form-control me-2" placeholder="بحث بالرقم" "
                    value="{{ request('search') }}">
                                                                            <button type="submit" class="btn btn-primary">بحث</button>
                                                                               @if (request('search'))
            <a href="{{ route('statices.orders') }}" class="btn m-2 btn-secondary">
                إلغاء البحث
            </a>
            @endif
        </form>

        <div class="btns">

            <a target="_blank" href="{{ route('orders.successed') }}" class="btn m-2 btn-outline-success"> ناجح</a>
            <a target="_blank" href="{{ route('orders.faild') }}" class="btn m-2 btn-outline-danger">فاشل</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">اسم العميل</th>
                <th scope="col">رقم العميل </th>
                <th scope="col">الحالة</th>
                <th scope="col">المبلغ</th>
                <th scope="col">إعدادات</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ str_replace(',', ' ', $order->userData->name) }}</td>
                    <td> {{ $order->phoneNumber }} </td>
                    <td>
                        @if ($order->status == 1)
                            <span class="bg-success  p-2 text-bg-success">اوردر ناجح</span>
                        @elseif ($order->status == 2)
                            <span class="bg-danger p-2 text-bg-success">اوردر فاشل</span>
                        @endif
                        {{-- {{ $order->status == 1 ? 'اورد ناجح' : 'other' }} --}}
                    </td>
                    <td>{{ $order->totalPrice }} </td>
                    <td>
                        <a type="button" target="_blank" href="{{ route('orders.show', $order->id) }}"
                            class="btn btn-outline-info btn-sm me-1">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a type="button" target="_blank" href="{{ route('users.edit', $order->id) }}"
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
                    <td colspan="6 ">
                        لا يوجد نتائج
                    </td>

                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
@endsection
