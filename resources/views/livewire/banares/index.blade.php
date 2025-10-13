<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">معرض البانرات الإعلانية</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">إدارة وعرض جميع البانرات الإعلانية في منصتك</p>
        </div>

        <!-- Add New Banner Button -->
        @can('section.create')
        <div class="flex justify-center mb-8">
            <a href="{{ route('banares.create') }}"
               class="group bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-lg">إضافة بانر جديد</span>
            </a>
        </div>
        @endcan

        <!-- Main Content Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/60 overflow-hidden">

            <!-- Gallery Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($data as $banar)
                    <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">

                        <!-- Image Container -->
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('uploads/' . $banar->path) }}"
                                 alt="Banar Image"
                                 class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-110">

                            <!-- Overlay on Hover -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="flex gap-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <!-- View Button -->
                                    <a href="{{ route('banares.show', $banar->id) }}"
                                       class="bg-white/20 backdrop-blur-sm text-white p-3 rounded-full hover:bg-white/30 transition-colors duration-300 border border-white/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('banares.edit', $banar->id) }}"
                                       class="bg-blue-500/80 backdrop-blur-sm text-white p-3 rounded-full hover:bg-blue-600 transition-colors duration-300 border border-blue-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <button wire:confirm="⚠️ هل أنت متأكد من حذف هذا البانر؟ سيتم حذف البيانات بشكل نهائي"
                                            wire:click="delete({{ $banar->id }})"
                                            class="bg-red-500/80 backdrop-blur-sm text-white p-3 rounded-full hover:bg-red-600 transition-colors duration-300 border border-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-4 bg-gradient-to-r from-white to-gray-50">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">ID: {{ $banar->id }}</span>
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">نشط</span>
                            </div>
                        </div>
                    </div>
                    @empty

                    <!-- Empty State -->
                    <div class="col-span-full">
                        <div class="text-center py-16">
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <img width="120" src="{{ asset('admin/photo/seo.png') }}" alt="No Data" class="opacity-80">
                                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full blur-lg opacity-50"></div>
                                </div>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد بانرات حالياً</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">لم يتم رفع أي بانرات إعلانية حتى الآن. ابدأ بإضافة أول بانر لك.</p>

                            <a href="{{ route('banares.create') }}"
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                إضافة أول بانر
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Statistics Bar -->
        @if($data->count() > 0)
        <div class="mt-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/60 p-6">
            <div class="flex flex-wrap justify-center gap-6 text-center">
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-blue-600">{{ $data->count() }}</span>
                    <span class="text-sm text-gray-600">إجمالي البانرات</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-green-600">{{ $data->count() }}</span>
                    <span class="text-sm text-gray-600">بانر نشط</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-orange-600">0</span>
                    <span class="text-sm text-gray-600">بانر مؤرشف</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Loading Animation -->
@section('styles')
<style>
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .grid > * {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .grid > *:nth-child(1) { animation-delay: 0.1s; }
    .grid > *:nth-child(2) { animation-delay: 0.2s; }
    .grid > *:nth-child(3) { animation-delay: 0.3s; }
    .grid > *:nth-child(4) { animation-delay: 0.4s; }
</style>
@endsection
