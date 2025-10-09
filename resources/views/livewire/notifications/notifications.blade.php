<div class="card card-primary">
    <div class="card-header">
        <h5 class="text-center mb-0">إضافة صنف جديد</h5>
    </div>

    <form role="form">
        <div class="card-body">
            <div class="row">
                <!-- العنوان -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">العنوان</label>
                        <input wire:model="title" type="text"
                            class="form-control @error('title') is-invalid @enderror" id="title"
                            placeholder="ادخل عنوان الإشعار">
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- الرسالة -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="message">الرسالة</label>
                        <input wire:model="message" type="text"
                            class="form-control @error('message') is-invalid @enderror" id="message"
                            placeholder="ادخل محتوي الرسالة">
                        @error('message')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- الصورة -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="photo">الصورة</label>
                        <input wire:model="photo" class="form-control @error('photo') is-invalid @enderror"
                            type="file" id="photo" accept="image/*">

                        <!-- مؤشر تحميل الصورة فقط أثناء الرفع -->
                        <div wire:loading wire:target="photo" class="mt-2">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                    <span class="sr-only">جاري التحميل...</span>
                                </div>
                                <span class="text-muted">جاري تحميل الصورة...</span>
                            </div>
                        </div>

                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <!-- عرض الصورة المؤقتة بدون مؤشر تحميل عليها -->
                        @if ($photo)
                            <div class="mt-2">
                                <img class="img-thumbnail w-50" src="{{ $photo->temporaryUrl() }}" alt="معاينة الصورة">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- النوع (عام / خاص) -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">نوع الإشعار</label>
                        <select wire:model.live="type" id="type"
                            class="form-control @error('type') is-invalid @enderror">
                            <option value="">اختر...</option>
                            <option value="general">عام</option>
                            <option value="user">خاص بمستخدم</option>
                            <option value="people">خاص بفئه</option>
                        </select>

                        <!-- مؤشر تحميل عند تغيير النوع -->
                        <div wire:loading wire:target="type" class="mt-1">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                    <span class="sr-only">جاري التحميل...</span>
                                </div>
                                <span class="text-muted">جاري التحديث...</span>
                            </div>
                        </div>

                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- اختيار المستخدم لو خاص -->
                @if ($type === 'user')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">اختر المستخدم</label>
                            <select wire:model="user_id" id="user_id"
                                class="form-control @error('user_id') is-invalid @enderror">
                                <option value="">-- اختر --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>

                            @error('user_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif
            </div>

            <!-- قسم الإعدادات المخصصة - يظهر فقط إذا كان النوع "خاص" -->
            @if ($type === 'people')
                <div class="border-top mt-4 pt-4">
                    <h4 class="mb-2">اعدادات مخصصة</h4>
                    <h6 class="text-muted mb-3">تخصيصات الأستهداف</h6>

                    <div class="row">
                        <!-- النوع (الجنس) -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">النوع</label>
                                <select wire:model="gender" id="gender"
                                    class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">اختر النوع...</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- المنطقة -->
<!-- المنطقة -->
<div class="col-md-4">
    <div class="form-group">
        <label for="area">المنطقة</label>

        <!-- المنطقة المختارة حالياً -->
        @if($area)
            @php
                $selectedArea = $filteredAreas->firstWhere('id', $area) ??
                              \App\Models\Delivery::find($area);
            @endphp
            @if($selectedArea)
                <div class="alert alert-success py-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>{{ $selectedArea->name }}</strong>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                wire:click="$set('area', '')" title="إلغاء الاختيار">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
        @endif

        <!-- حقل البحث -->
        <input
            type="text"
            class="form-control mb-2"
            placeholder="ابحث عن المنطقة..."
            wire:model.live="areaSearch"
        >

        <!-- Select مع بيانات مصفاة -->
        <select wire:model.live="area" id="area"
            class="form-control @error('area') is-invalid @enderror" size="5">
            <option value="">اختر المنطقة...</option>
            @foreach ($filteredAreas as $areaItem)
                <option value="{{ $areaItem->id }}"
                    {{ $area == $areaItem->id ? 'selected' : '' }}>
                    {{ $areaItem->name }}
                </option>
            @endforeach
        </select>

        @error('area')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <!-- مؤشر التحميل -->
        <div wire:loading wire:target="areaSearch" class="mt-1">
            <small class="text-primary">🔍 جاري البحث...</small>
        </div>

        <!-- عدد النتائج -->
        @if ($areaSearch && !$filteredAreas->isEmpty())
            <small class="text-muted">
                عرض {{ $filteredAreas->count() }} منطقة
            </small>
        @endif

        <!-- لا توجد نتائج -->
        @if ($areaSearch && $filteredAreas->isEmpty())
            <small class="text-danger">
                ❌ لا توجد مناطق تطابق "{{ $areaSearch }}"
            </small>
        @endif
    </div>
</div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="age">العمر</label>

                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" wire:model.live="minAge"
                                            class="form-control @error('minAge') is-invalid @enderror"
                                            placeholder="من عمر" min="1" max="120">
                                        @error('minAge')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <input type="number" wire:model.live="maxAge"
                                            class="form-control @error('maxAge') is-invalid @enderror"
                                            placeholder="إلى عمر" min="1" max="120">
                                        @error('maxAge')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- عرض Range النهائي -->
                                @if ($minAge && $maxAge)
                                    <div class="mt-2 p-2 bg-light rounded text-center">
                                        <small class="text-muted">الفئة العمرية المحددة:</small>
                                        <div class="font-weight-bold text-primary">{{ $minAge }} -
                                            {{ $maxAge }} سنة</div>
                                    </div>
                                @endif
                            </div>
                        </div>
            @endif

            <!-- رسالة النجاح -->
            @if (session('done'))
                <div class="alert alert-success alert-dismissible mt-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle mr-2"></i>
                        <span>{{ session('done') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        <!-- /.card-body -->

        <div class="card-footer text-left">
            <button wire:click.prevent="send" id="done" class="btn btn-primary px-4"
                wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="send">إضافة</span>
                <span wire:loading wire:target="send">
                    <span class="spinner-border spinner-border-sm mr-2" role="status"></span>
                    جاري الإرسال...
                </span>
            </button>

            <button type="button" class="btn btn-secondary px-4 mr-2">إلغاء</button>
        </div>
    </form>
</div>
