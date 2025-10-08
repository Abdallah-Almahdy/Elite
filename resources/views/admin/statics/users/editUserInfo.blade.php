@extends('admin.app')

@section('content')
    <table class="table table-bordered">

        <tbody>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                <tr>
                    <th scope="row">الأسم</th>
                    <td>
                        <input type="text" value="{{ str_replace(',', ' ', $user->name) }}" name="name"
                            class="form-control">

                    </td>
                </tr>
                <tr>
                    <th scope="row">الرقم</th>
                    <td>
                        <input type="number" value="{{ $user->email }}" name="email" class="form-control">
                    </td>
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
    <button class="btn btn-primary" type="submit">تحديث</button>
    </form>

@endsection
