<div class="bg-white shadow rounded-2xl p-6">
    <h3 class="text-xl font-bold text-center text-gray-700 mb-6">إضافة إشعار جديد</h3>

    <form wire:submit.prevent="send" class="space-y-6">

        {{-- العنوان --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-600">العنوان</label>
            <input wire:model="title" id="title" type="text"
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                   placeholder="ادخل عنوان الإشعار">
            @error('title')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- الرسالة --}}
        <div>
            <label for="message" class="block text-sm font-medium text-gray-600">الرسالة</label>
            <textarea wire:model="message" id="message" rows="3"
                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      placeholder="ادخل محتوى الرسالة"></textarea>
            @error('message')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- الصورة --}}
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-600">الصورة</label>
            <input wire:model="photo" id="photo" type="file"
                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                   file:rounded-full file:border-0 file:text-sm file:font-semibold
                   file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

            @if ($photo)
                <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-20 rounded shadow">
            @endif

            @error('photo')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- النوع --}}
        <div>
            <label for="type" class="block text-sm font-medium text-gray-600">نوع الإشعار</label>
            <select wire:model="type" id="type"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">اختر...</option>
                <option value="general">عام</option>
                <option value="user">خاص بمستخدم</option>
            </select>
            @error('type')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- اختيار المستخدم --}}
        @if($type === 'user')
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-600">اختر المستخدم</label>
                <select wire:model="user_id" id="user_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">-- اختر --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        @endif

        {{-- تخصيصات إضافية (تظهر فقط لو عام) --}}
        @if($type === 'general')
            <div class="border-t pt-4">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">إعدادات مخصصة</h4>
                <p class="text-sm text-gray-500 mb-4">تخصيصات الاستهداف</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- النوع --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600">النوع</label>
                        <select wire:model="section" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">اختر...</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>

                    {{-- المنطقة --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600">المنطقة</label>
                        <select multiple wire:model="areas"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="الصباح">الصباح</option>
                            <option value="الغريب">الغريب</option>
                            <option value="الموشي">الموشي</option>
                            <option value="السلام">السلام</option>
                            <option value="الملاحة">الملاحة</option>
                            <option value="ابراج السحاب">ابراج السحاب</option>
                            <option value="بورتوفيق">بورتوفيق</option>
                        </select>
                    </div>

                    {{-- العمر --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600">العمر</label>
                        <select wire:model="age_range" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">اختر...</option>
                            <option value="18-25">18-25</option>
                            <option value="25-30">25-30</option>
                            <option value="30-40">30-40</option>
                            <option value="40-55">40-55</option>
                            <option value="55+">55+</option>
                        </select>
                    </div>
                </div>
            </div>
        @endif

        {{-- رسالة نجاح --}}
        @if (session('done'))
            <div class="rounded-lg bg-green-100 p-3 text-green-700 flex items-center">
                <i class="fa fa-check-circle text-xl mr-2"></i>
                <span>{{ session('done') }}</span>
            </div>
        @endif

        {{-- زر الإرسال --}}
        <div class="pt-4">
            <button type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                إرسال الإشعار
            </button>
        </div>
    </form>
</div>
