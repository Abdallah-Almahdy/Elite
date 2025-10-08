@extends('admin.app')

@section('content')
    <table class="table table-bordered">

        <tbody>
            <tr>
                <th scope="row">الأسم</th>
                <td>{{ str_replace(',', ' ', $user->name) }}</td>
            </tr>
            <tr>
                <th scope="row">الرقم</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th scope="row">تاريخ الميلاد</th>
                <td>{{ $user->customerInfo->birthDate }}</td>
            </tr>
            <tr>
                <th scope="row">العنوان الأول</th>
                @if ($addressCountryName)
                    <td>
                        {{ $addressCountryName ?? 'لا يوجد منطقة' . ', ' . $user->customerInfo->addresscity }}

                        @if ($user->customerInfo->addressstreet)
                            , {{ $user->customerInfo->addressstreet }}
                        @endif

                        {{ ' ' . $user->customerInfo->addressbuildingNumber }}

                        @if ($user->customerInfo->addressfloorNumber)
                            , الدور: {{ $user->customerInfo->addressfloorNumber }}
                        @endif

                        , رقم الشقة: {{ $user->customerInfo->addressApartmentNumber }}
                        @if ($user->customerInfo->disSign)
                            , علامة مميزة: {{ $user->customerInfo->disSign }}
                        @endif
                    </td>
                @else
                    <td>لا يوجد</td>
                @endif
            </tr>
            <tr>
                <th scope="row">العنوان الثاني</th>
                @if ($addressCountry2Name)
                    <td>
                        {{ $addressCountry2Name ?? 'لا يوجد منطقة' . ', ' . $user->customerInfo->addresscity }}

                        @if ($user->customerInfo->addressstreet)
                            , {{ $user->customerInfo->addressstreet }}
                        @endif

                        {{ ' ' . $user->customerInfo->addressbuildingNumber }}

                        @if ($user->customerInfo->addressfloorNumber)
                            , الدور: {{ $user->customerInfo->addressfloorNumber }}
                        @endif

                        , رقم الشقة: {{ $user->customerInfo->addressApartmentNumber }}
                        @if ($user->customerInfo->disSign)
                            , علامة مميزة: {{ $user->customerInfo->disSign }}
                        @endif
                    </td>
                @else
                    <td>لا يوجد</td>
                @endif
            </tr>
            <tr>
                <th scope="row">صورة المستخدم</th>
                @if ($user->customerInfo->profileImage)
                    <td>

                        <img width="150" height="150" src="{{ asset($user->customerInfo->profileImage) }}"
                            alt="">
                    </td>
                @else
                    <td>لا يوجد</td>
                @endif

            </tr>

        </tbody>
    </table>

    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary" type="submit">تعديل</a>




    <h1 class="mb-3 mt-3"> طلبات المستخدم </h1>
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
            @forelse ($userOrders as $order)
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
@endsection
