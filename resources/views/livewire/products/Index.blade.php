    <div>


        <div class="container">
            <div class="mt-4 mb-4">

                <!--   search -->

                <!-- Search Bar -->


                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif





                <!-- New Section Selection -->
                <div class="space-y-4">

                    <!-- Toggle Button -->


                    <!-- Conditional Section -->
                    @if ($showBulkActions)
                        <div class="space-y-4">

                            <!-- Section Select -->
                            <div class="relative">
                                <label for="newSectionId" class="block text-sm font-medium text-gray-700 mb-1">
                                    اختر القسم الجديد:
                                </label>
                                <div class="relative">
                                    <select wire:model="newSectionId" id="newSectionId"
                                        class="appearance-none w-full rounded-full border border-gray-300 bg-white py-2.5 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">اختر قسم</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Chevron Icon -->
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Bulk Action Buttons -->
                            <div class="flex gap-3">
                                <button type="button" wire:click="updateSection"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium transition">
                                    <i class="fas fa-sync-alt mr-2"></i> تحديث الأقسام
                                </button>

                                <button type="button" wire:click="deleteSelected"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">
                                    <i class="fas fa-trash-alt mr-2"></i> حذف المحدد
                                </button>
                            </div>
                        </div>
                    @endif

                </div>


            </div>



            <div class="card shadow-none">
                <div class="overflow-x-auto rounded-md shadow-sm border border-gray-200">
                    <table
                        class="min-w-full divide-y text-center divide-gray-200 text-sm text-right text-gray-700 bg-white">
                        <thead class="bg-blue-200 text-gray-700  text-xs font-semibold uppercase">
                            <tr>
                                <th colspan="5" class="px-3 py-3 bg-blue-50  shadow-none border-transparent">

                                    <div class="flex justify-between items-center gap-2">
                                        <div class="flex justify-between items-center gap-2">

                                            @can('product.create')
                                                <a href="{{ route('products.create') }}"
                                                    class="   btn btn-outline-success right fas p-1 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>


                                                </a>
                                            @endcan
                                            <button type="button" wire:click="toggleBulkActions"
                                                class="   btn btn-outline-primary right fas p-1 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>



                                            </button>



                                            <button type="button" wire:click="filterZeroStock"
                                                class="   btn btn-outline-info right fas p-1 "
                                                title="{{ $showZeroStock ? 'عرض جميع المنتجات' : 'عرض المنتجات التي نفذت' }}">
                                                @if ($showZeroStock)
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                                    </svg>
                                                @endif
                                            </button>





                                        </div>

                                        <div class="flex justify-between items-center gap-2">
                                            {{-- search text box --}}
                                            <input wire:model.defer="search" id="search"
                                                placeholder="ابحث عن منتج..."
                                                class="w-56 sm:w-72 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm
                                        focus:border-blue-500 focus:ring-blue-500 outline-none">

                                            {{-- search (magnifier) button --}}
                                            <button type="button" wire:click="searchProduct"
                                                class="   btn btn-outline-primary right fas p-1 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                </svg>

                                            </button>

                                            {{-- clear (×) button --}}
                                            <button type="button" wire:click="resetViewLinks"
                                                class="   btn btn-outline-danger right fas p-1 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18 18 6M6 6l12 12" />
                                                </svg>

                                            </button>
                                        </div>


                                    </div>
                                </th>

                            </tr>
                            <tr>
                                <th class="px-3 py-2 border border-gray-200 w-[10px]"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </th>
                                <th class="px-3 py-2 border border-gray-200 w-[10px]">صورة</th>
                                <th class="px-3 py-2 border border-gray-200">الأسم</th>
                                <th class="px-3 py-2 border border-gray-200">القسم</th>
                                <th class="px-3 py-2 border border-gray-200">خيارات</th>
                            </tr>

                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($products as $product)
                                <tr
                                    class="@if ($product->qnt == 0) bg-yellow-100 @else hover:bg-gray-50 @endif">
                                    <td class="px-3 py-2 border border-gray-200">
                                        <input type="checkbox" wire:model="selectedProducts"
                                            value="{{ $product->id }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>

                                    <td class="px-3 py-2 border border-gray-200">
                                        <div class="relative w-12 h-12">
                                            <img class="w-12 h-12 rounded-full object-cover border border-gray-300"
                                                src="{{  asset('storage/' . $product->photo)   ?? asset('products/img.png') }}"
                                                alt="img">

                                            @if (empty($product->photo))
                                                <div class="absolute inset-0 flex items-center justify-center text-white bg-black bg-opacity-40 cursor-pointer"
                                                    data-product-id="{{ $product->id }}">
                                                    <span>رفع صورة</span>
                                                </div>
                                                <input type="file" wire:model="photo"
                                                    wire:change="uploadPhoto({{ $product->id }})" class="hidden"
                                                    id="file-input-{{ $product->id }}" />
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-3 py-2 border border-gray-200">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-3 py-2 border border-gray-200">
                                        {{ optional($product->section)->name ?? 'غير متاح' }}
                                    </td>

                                    <td class="px-3 py-2 border border-gray-200 text-center">
                                        <div class="inline-flex items-center justify-center gap-1">




                                            <a href="{{ route('products.show', $product->id) }}">
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
                                            @can('product.edit')
                                                <a href="{{ route('products.edit', $product->id) }}">

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
                                            @if ($product->qnt > 0)
                                                <button wire:click="changeAvailability({{ $product->id }}, 0)"
                                                    class="   btn btn-outline-danger right fas p-1 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>

                                                </button>
                                            @else
                                                <button wire:click="changeAvailability({{ $product->id }}, 1000)"
                                                    class="   btn btn-outline-success right fas p-1 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                                                    </svg>

                                                </button>
                                            @endif

                                        </div>
                                    </td>




                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
            @if ($viewLinks)
                <div class="mt-4">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('livewire:load', function() {
                // When the hover text is clicked, trigger the corresponding file input click
                document.querySelectorAll('.upload-text').forEach(function(element) {
                    element.addEventListener('click', function() {
                        var productId = element.getAttribute('data-product-id'); // Get the product ID
                        var fileInput = document.getElementById('file-input-' +
                            productId); // Find the associated file input
                        if (fileInput) {
                            fileInput.click(); // Trigger the file input click
                        }
                    });
                });
            });
        </script>
    </div>
