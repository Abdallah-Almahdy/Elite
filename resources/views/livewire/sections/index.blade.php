        <div>
            <div class="container">



                <div class="card shadow-none">

                    <div class="card-body table-responsive p-0">

                        <table class="table table-hover   table-bordered text-center text-nowrap">
                            <thead class="bg-blue-200">
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
                                            <a target="_blank" href="{{ route('sections.main') }}"> <span
                                                    class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset">رئيسي</span>
                                            </a>


                                        </td>
                                        <td>

                                            <a href="{{ route('sectionsInfo', $section->id) }}">
                                                <span class=" btn btn-outline-info p-1 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </span>
                                            </a>
                                            @can('section.edit')
                                                <a href="{{ route('sections.edit', $section->id) }}">

                                                    <span class=" btn btn-outline-info p-1 ">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                        </svg>

                                                    </span>
                                                </a>
                                            @endcan


                                            @can('section.delete')
                                                <button wire:confirm="سيتم حذف هذا القسم"
                                                    wire:click="delete({{ $section->id }})"
                                                    class="   btn btn-outline-danger right fas p-1 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>

                                    <!-- Display SubSections under this Section -->
                                    @foreach ($subSections as $subSection)
                                        @if ($subSection->main_section_id == $section->id)
                                            <tr id="sub-{{ $subSection->id }}" class="pr-4 border-end">
                                                <td>
                                                    <img class="rounded-circle" width="50" height="50"
                                                        src="{{ asset('uploads/' . ($subSection->photo ?? 'default-photo.jpg')) }}"
                                                        alt="img">
                                                </td>
                                                <td>{{ $subSection->name }}</td>

                                                <td>
                                                    <a target="_blank" href="{{ route('sections.sub') }}"> <span
                                                            class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-gray-500/10 ring-inset">فرعي</span>
                                                    </a>


                                                </td>
                                                <td>
                                                    <a href="{{ route('sections.show', $subSection->id) }}">
                                                        <span class=" btn btn-outline-info p-1 ">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            </svg>
                                                        </span>

                                                    </a>
                                                    @can('section.edit')
                                                        <a href="{{ route('sections.edit', $subSection->id) }}">

                                                            <span class=" btn btn-outline-info p-1 ">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor" class="size-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                                </svg>
                                                            </span>
                                                        </a>
                                                    @endcan


                                                    @can('section.delete')
                                                        <button wire:confirm="سيتم حذف هذا القسم"
                                                            wire:click="delete({{ $subSection->id }})"
                                                            class="   btn btn-outline-danger right fas p-1 ">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

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
