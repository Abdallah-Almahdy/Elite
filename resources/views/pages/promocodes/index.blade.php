@extends('admin.app')


@section('content')
    <a {{-- href="{{ route('promocodes.report') }}" --}} class="btn btn-primary m-2 ">تقرير</a>

    <a href="{{ route('promocodes.create') }}"
        class="btn btn-outline-success w-100 mb-2 text-center d-flex align-items-center justify-content-center ">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

    </a>
    <div class="card shadow-none">
        {{-- <a href="{{ route('promocodes.create') }}" class="btn btn-primary float-left">إضافة كود جديد</a> --}}


        <div class="card-body shadow-none table-responsive p-0">
            <table class="table table-hover   table-bordered text-center text-nowrap">
                <thead class="bg-blue-200">
                    <tr>


                        <th>الكود</th>
                        <th> نوع الكود</th>
                        <th> اسم المستخدم</th>
                        <th> عدد الأكواد </th>
                        <th> العدد المتبقي</th>
                        <th> الحد الأدني للطلب</th>
                        <th> نوع الخصم </th>
                        <th> قيمه الخصم </th>
                        <th>تاريخ اللإنتهاء</th>
                        <th> التفعيل </th>
                        <th> التفعيل مع العروض </th>
                        <th>خيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $product)
                        <tr class="borderd table-bordered">






                            <td>{{ $product->code }}</td>
                            <td>
                                @if ($product->promo_cat == 'user')
                                    مستخدم واحد
                                @endif
                                @if ($product->promo_cat == 'all')
                                    مستخدمين متعددين
                                @endif

                                (@if ($product->type == 'limited')
                                    محدود
                                @endif
                                @if ($product->type == 'unlimited')
                                    غير محدود
                                @endif)
                            </td>
                            <td>
                                @if ($product->promo_cat == 'all')
                                    -
                                @else
                                    {{ str_replace(',', ' ', $product->user->name) }}
                                @endif
                            </td>

                            <td>
                                @if ($product->promo_cat == 'user')
                                    -
                                @else
                                    {{ $product->users_limit ?? '-' }}
                                @endif
                            </td>
                            <td>
                                @if ($product->promo_cat == 'user')
                                    -
                                @else
                                    {{ $product->available_codes ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $product->min_order_value }} ج</td>
                            <td>
                                @if ($product->discount_type == 'percentage')
                                    نسبة
                                @endif
                                @if ($product->discount_type == 'cash')
                                    نقدي
                                @endif
                            </td>
                            <td>
                                @if ($product->discount_type == 'percentage')
                                    {{ $product->discount_percentage_value }}%
                                @endif
                                @if ($product->discount_type == 'cash')
                                    {{ $product->discount_cash_value }} ج
                                @endif
                            </td>
                            <td>{{ $product->expiry_date->format('d-m-Y') }}</td>


                            <td>
                                <div class="flex justify-center items-center  ">

                                    @if (!$product->active)
                                        <span class="relative flex justify-center items-center size-3"> <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex size-3 rounded-full bg-red-500"></span></span>
                                    @else
                                        <span class="relative flex justify-center items-center size-3"> <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                            <span
                                                class="relative inline-flex size-3 rounded-full bg-green-500"></span></span>
                                    @endif

                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center items-center  ">

                                    @if (!$product->check_offer_rate)
                                        <span class="relative flex justify-center items-center size-3"> <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex size-3 rounded-full bg-red-500"></span></span>
                                    @else
                                        <span class="relative flex justify-center items-center size-3"> <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                            <span
                                                class="relative inline-flex size-3 rounded-full bg-green-500"></span></span>
                                    @endif

                                </div>
                            </td>




                            <td>
                                @can('product.edit')
                                    <a href="{{ route('promocodes.edit', $product->id) }}">

                                        <span class=" btn btn-outline-info p-1 ">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>

                                        </span>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
