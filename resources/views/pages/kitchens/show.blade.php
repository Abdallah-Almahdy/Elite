@extends('admin.app')

@section('content')
    @if (session('success'))
        <div class="callout bg-success     callout-success">
            <h5 class=" flex-row flex  ">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p>
                    {{ session('success') }}
                </p>




            </h5>
        </div>
    @endif


    <h2 class="text-center my-4 bg-primary rounded p-2"> معلومات المطبخ</h2>

    <table class="table table-bordered table-hover">

        <tbody>
            <tr class="text-center">
                <td> اسم المطبخ</td>
                <td> {{ $kitchen->name }}</td>

            </tr>
            <tr class="text-center">
                <td> حالة التفعيل</td>
                <td>

                    @if ($kitchen->active)
                        <span class="badge rounded-circle bg-success d-inline-block"
                            style="width: 15px; height: 15px;"></span>
                    @else
                        <span class="badge rounded-circle bg-danger d-inline-block"
                            style="width: 15px; height: 15px;"></span>
                    @endif
                </td>

            </tr>

        </tbody>
    </table>

    <h2 class="text-center my-4 bg-primary rounded p-2">طابعات المطبخ</h2>

    <table class="table table-bordered table-hover">
        <thead>
            <tr class="bg-gradient-info text-center">
                <th scope="col">الاسم</th>
                <th scope="col">الموديل</th>
                <th scope="col ">حالة التفعيل </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kitchen->Printers as $Printer)
                <tr class="text-center">
                    <td> {{ $Printer->name }}</td>
                    <td> {{ $Printer->model }}</td>
                    <td>
                        @if ($Printer->active)
                            <span class="badge rounded-circle bg-success d-inline-block"
                                style="width: 15px; height: 15px;"></span>
                        @else
                            <span class="badge rounded-circle bg-danger d-inline-block"
                                style="width: 15px; height: 15px;"></span>
                        @endif
                    </td>

                </tr>
            @empty
                <td colspan="3 text-center bg-danger">لا يوجد طابعات مربوطة بهذا المطبخ</td>
            @endforelse

        </tbody>

    </table>

    <h2 class="text-center my-4 bg-primary rounded p-2">أقسام المطبخ</h2>

    <table class="table table-bordered table-hover">
        <thead>
            <tr class="bg-gradient-info text-center">
                <th scope="col">الاسم</th>
                <th scope="col ">حالة التفعيل </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kitchen->subSections as $subSection)
                <tr class="text-center">
                    <td> {{ $subSection->name }}</td>
                    <td>
                        @if ($subSection->active)
                            <span class="badge rounded-circle bg-success d-inline-block"
                                style="width: 15px; height: 15px;"></span>
                        @else
                            <span class="badge rounded-circle bg-danger d-inline-block"
                                style="width: 15px; height: 15px;"></span>
                        @endif
                    </td>

                </tr>
            @empty
                <td colspan="3 text-center bg-danger">لا يوجد أقسام مربوطة بهذا المطبخ</td>
            @endforelse

        </tbody>
    </table>
@endsection
