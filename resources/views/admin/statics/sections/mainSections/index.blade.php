@extends('admin.app')

@section('content')
    <div>
        <div class="container">



            <div class="card">

                <div class="card-body table-responsive p-0">

                    <table class="table table-hover  table-bordered text-center text-nowrap">
                        <thead class="bg-gradient-info">
                            <tr>
                                <th width="10px">صورة</th>
                                <th class="">الأسم</th>
                                <th>النوع</th>
                                <th>خيارات</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                                <!-- Display Main Section -->
                                <tr>
                                    <td>
                                        <img class="rounded-circle" width="50" height="50"
                                            src="{{ asset('uploads/' . ($section->photo ?? 'default-photo.jpg')) }}"
                                            alt="img">
                                    </td>
                                    <td>{{ $section->name }}</td>
                                    <td>
                                        <span class="badge badge-pill p-2 badge-primary">

                                            <i class="fa fa-university" aria-hidden="true"></i>
                                            رئيسي
                                        </span>
                                    </td>
                                    <td>

                                        <a target="_blank" href="{{ route('sectionsInfo', $section->id) }}">
                                            <span class=" btn btn-outline-info p-1 ">
                                                معاينة
                                                <i class="right fas bg-transparent text-lg fa-eye"></i>
                                            </span>
                                        </a>
                                        @can('section.edit')
                                            <a href="{{ route('sections.edit', $section->id) }}">

                                                <span class=" btn btn-outline-info p-1 ">
                                                    تعديل
                                                    <i class="right fas text-lg fa-pen"></i>
                                                </span>
                                            </a>
                                        @endcan


                                        @can('section.delete')
                                            <button wire:confirm="سيتم حذف هذا القسم" wire:click="delete({{ $section->id }})"
                                                class="   btn btn-outline-danger right fas p-1 ">
                                                حذف
                                                <i class="right fas text-lg  fa-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>

                                <!-- Display SubSections under this Section -->

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="text-danger">لا يوجد اقسام</div>
                                        <img width="50" src="{{ asset('admin/photo/seo.png') }}">
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Links for Sections -->
            <div class="mt-4">
                {{ $sections->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
