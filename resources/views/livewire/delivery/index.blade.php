<div class="p-4 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">إدارة المناطق</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        <!-- أزرار التحكم الرئيسية -->
        <div class="flex flex-wrap gap-3 mb-6">
            <button wire:click="$toggle('showDeliverySection')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                {{ $showDeliverySection ? 'إخفاء خيار التوصيل المجاني' : 'إظهار خيار التوصيل المجاني' }}
            </button>

            <button wire:click="$toggle('showDeliveryForm')"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ $showDeliveryForm ? 'إخفاء نموذج المنطقة' : 'إظهار نموذج المنطقة' }}
            </button>
        </div>

        <!-- قسم التوصيل المجاني -->
        @if ($showDeliverySection)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">إعدادات التوصيل المجاني</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    <div class="form-group">
                        <label for="is_free" class="block text-sm font-medium text-gray-700 mb-1">تفعيل التوصيل المجاني</label>
                        <select wire:model="is_free" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="is_free" name="is_free" required>
                            <option value="">اختر حالة التوصيل المجاني</option>
                            <option value="1">تفعيل</option>
                            <option value="2">تعطيل</option>
                        </select>
                        @error('is_free')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex justify-start">
                        <button wire:click="handleFree" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            تطبيق الإعدادات
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- نموذج إضافة/تعديل منطقة -->
        @if ($showDeliveryForm)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ $edit_id ? 'تعديل المنطقة' : 'إضافة منطقة جديدة' }}</h3>
                <form wire:submit.prevent="{{ $edit_id ? 'updateDelivery' : 'addDelivery' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم المنطقة</label>
                            <input type="text" id="name" wire:model="name"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="delivery_price" class="block text-sm font-medium text-gray-700 mb-1">سعر التوصيل</label>
                            <input type="text" id="delivery_price" wire:model="delivery_price"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('delivery_price')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <button type="submit"
                                class="px-5 py-2.5 {{ $edit_id ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ $edit_id ? 'تحديث' : 'إضافة' }}
                        </button>

                        @if ($edit_id)
                            <button type="button" wire:click="cancelEdit"
                                    class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                إلغاء التعديل
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        @endif

        <!-- قائمة المناطق -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center">
                <h3 class="text-lg font-semibold text-gray-700 mb-2 md:mb-0">قائمة المناطق</h3>
                <div class="w-full md:w-64">
                    <input type="text" wire:model="search" wire:keyup="makeSearch"
                           placeholder="ابحث عن منطقة..."
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-right font-semibold text-gray-700 border-b">مسلسل</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-700 border-b">المنطقة</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-700 border-b">سعر التوصيل</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-700 border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveries as $delivery)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 border-b text-gray-600">{{ $delivery->id }}</td>
                                <td class="py-3 px-4 border-b text-gray-800">{{ $delivery->name }}</td>
                                <td class="py-3 px-4 border-b text-gray-800">{{ $delivery->delivery_price }}</td>
                                <td class="py-3 px-4 border-b">
                                    <div class="flex gap-2 justify-end">
                                        <button wire:click="editDelivery({{ $delivery->id }})"
                                                class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-1 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            تعديل
                                        </button>
                                        <button wire:click="deleteDelivery({{ $delivery->id }})"
                                                onclick="return confirm('هل أنت متأكد من أنك تريد حذف هذه المنطقة؟')"
                                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition flex items-center gap-1 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($deliveries->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2">لا توجد مناطق لعرضها</p>
                </div>
            @endif
        </div>
    </div>
</div>
