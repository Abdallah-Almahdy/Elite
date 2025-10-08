@extends('admin.app')

@section('content')
    {{-- user data --}}
    <h1 class="mb-3">بيانات العميل</h1>
    <table class="table table-bordered">

        <tbody>
            <tr>
                <th scope="row">الأسم</th>
                <td> <a target="_blank"
                        href="{{ route('users.show', $user->id) }}">{{ str_replace(',', ' ', $user->name) }}</a> </td>
            </tr>
            <tr>
                <th scope="row">الرقم</th>
                <td>{{ $user->email }}</td>
            </tr>
            @if ($order->address == 1)
                <tr>
                    <th scope="row">العنوان </th>
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
            @elseif ($order->address == 2)
                <tr>
                    <th scope="row">العنوان </th>
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
            @endif




        </tbody>
    </table>

    {{-- order data --}}
    <h1 class="mb-3"> تفاصيل الطلب </h1>
    <table class="table table-bordered">

        <tbody>
            <tr>
                <th scope="row">الحالة</th>
                <td>
                    @if ($order->status == 0)
                        @if ($order->orderTracking[0]->status == 0)
                            اوردر جديد
                        @elseif ($order->orderTracking[0]->status == 1)
                            يتم تجهيز الاوردر
                        @elseif ($order->orderTracking[0]->status == 2)
                            يتم توصيل الاوردر
                        @endif
                    @elseif ($order->status == 1)
                        اوردر ناجح وتم التوصيل
                    @elseif ($order->status == 2)
                        فشل الاوردر
                    @endif


                </td>
            </tr>
            <tr>
                <th scope="row">طريقة الدفع</th>
                <td>
                    @if ($order->payment_method == 0)
                        كاش
                        <img width="100" height="50" src="{{ asset('logos/instapay.png') }}" alt="">
                    @elseif($order->payment_method == 1)
                        فيزا
                    @elseif($order->payment_method == 2)
                        محفظة
                    @elseif($order->payment_method == 3)
                        <img width="100" height="50" src="{{ asset('logos/instapay.png') }}" alt="">
                    @else
                        غير محدد
                    @endif
                </td>
            </tr>
            <tr>
                <th scope="row">المبلغ</th>
                <td>{{ $order->totalPrice }}</td>
            </tr>

            @if ($order->promo_code_id)
                <tr>
                    <th scope="row">البرومو كود المستخدم </th>
                    <td>{{ $order->promo_name }} </td>
                </tr>
            @endif




        </tbody>
    </table>
    {{-- orders products data --}}
    <h1 class="mb-3"> تفاصيل الفاتورة </h1>
    <table class="table table-hover table-bordered">
        <thead>
            <tr class="bg-gradient-primary">
                <th scope="col">#</th>
                <th scope="col">الصورة</th>
                <th scope="col">اسم المنتج</th>
                <th scope="col">القسم</th>
                <th scope="col">الكمية</th>
                <th scope="col">السعر للوحدة</th>
                <th scope="col">الإجمالي</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($orderProdutcs as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset('uploads/' . $product['porductData']->photo) }}" alt="image"
                            style="width: 30px; height: 30px; object-fit: contain;">
                    </td>
                    <td><a target="_blank"
                            href="{{ route('products.show', $product['porductData']->id) }}">{{ $product['porductData']->name }}</a>
                    </td>
                    <td> {{ $product['porductData']->section->name }}</td>


                    {{-- <img class="rounded-circle" width="50" height="50"
                    src="{{ asset('uploads/' . $product->photo) ?? asset('path/to/default-image.jpg') }}"
                    alt="Product image" style="cursor: pointer;"> --}}



                    <td>{{ $product['porductCount'] }}</td>
                    <td>{{ $product['porductPrice'] }}</td>
                    <td>{{ $product['porductTotalPrice'] }}</td>
                <tr>
            @endforeach
            <tr>

                <td colspan="6"> الإجمالي</td>
                <td>{{ $order['totalPrice'] }}</td>
            </tr>
        </tbody>
    </table>
@endsection
