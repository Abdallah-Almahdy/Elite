@extends('admin.app')
@section('links')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href=" {{ asset('AdminLTE-3-RTL/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection
@section('content')
    <div class="d-flex justify-content-between m-2 mb-0 align-items-center">


        @can('section.create')
            <th>
                <a href="{{ route('sections.create') }}"
                    class="btn btn-outline-success w-100 text-center d-flex align-items-center justify-content-center ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                </a>
            </th>
        @endcan

    </div>

    <livewire:sections.index />
@endsection
