@extends('admin.app')

@section('content')
    <table class="table table-bordered">

        <tbody>
            <tr>
                <td>اسم الصنف</td>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <td>السعر</td>
                <td>{{ $product->price }}</td>
            </tr>
            <tr>
                <td>الوصف</td>
                <td>{{ $product->description ?? '-' }}</td>
            </tr>

            <tr>
                <td>القسم</td>
                <td>{{ $product->section->name }}</td>
            </tr>

            @if ($product->active)
                <tr>
                    <td>الكمية</td>
                    <td>{{ $product->qnt }}</td>
                </tr>
            @endif
            <tr>
                <td>الصورة</td>
                <td>


                    <img style="width: 100px; height: 100px; object-fit: contain;"
                        src="{{ asset('uploads/' . $product->photo) }}" alt="Card image cap">
                </td>
            </tr>
        </tbody>
    </table>
@endsection
