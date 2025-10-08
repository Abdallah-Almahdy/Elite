<div>
    <div>
        <div class="card card-primary">
            <div class="card-header">
                <h5 class=" text-center">تعديل المنتج -{{ $data->name }}-</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form wire:submit="update" enctype="multipart/form-data" role="form">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">ادخل الاسم الجديد</label>
                        <input wire:model="name" type="text" class="form-control" id="name"
                            placeholder="{{ $data->name }}">
                    </div>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="card-body">

                        <div class="col-sm-6 ">
                            <div class="form-group">
                                <label for="price">السعر</label>
                                <input wire:model="price" type="text" class="form-control" id="price"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d*\.\d*).*$/, '$1').replace(/^(\d*\.).*?\./, '$1')"



                                    placeholder=" {{ $data->price }}">
                            </div>
                        </div>
                    </div>
                    @error('price')
                        <div class="text-danger"> {{ $message }}</div>
                    @enderror

                    <div class="col-sm-6 ">
                        <div class="form-group">
                            <label for="offer_rate">الخصم</label>
                            <input wire:model="offer_rate" type="text" class="form-control" id="offer_rate"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                                placeholder=" {{ $data->offer_rate }}">
                        </div>
                    </div>

                </div>
                @error('offer_rate')
                    <div class="text-danger"> {{ $message }}</div>
                @enderror


                <div class="flex-row d-flex ">

                    <div class="col-sm-6">

                        <div class="form-group">
                            <label for="section">القسم الجديد</label>
                            <select wire:model="section" id="section" class="form-control  " style="width: 100%;">


                                <option selected class="text-gray">
                                    الختر القسم الجديد
                                </option>

                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    @error('section_id')
                        <div class="text-danger"> الرجاء اختيار القسم</div>
                    @enderror
                </div>

                <div class="col-sm-6 ">

                    <div class="form-group">
                        <label for="description"> الوصف</label>
                        <input wire:model="description" type="text" class="form-control" id="description"
                            placeholder=" {{ $data->description }}    ">
                    </div>
                </div>

                <div class="col-sm-6 flex-row">
                    <div class="mb-3">
                        <label for="photo" class="form-label">احتر الصورة الجديدة</label>
                        <input wire:model="photo" class="form-control" type="file" id="photo">
                    </div>

                    @if ($photo)
                        <img class="border  w-25" src="{{ $photo->temporaryUrl() }}">
                    @else
                        <img class="border  w-25" src="{{ asset('uploads/' . $data->photo) }}">
                    @endif

                </div>
                @error('photo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror



                <div class="flex-row d-flex ">

                    <div class="col-sm-6">

                        <div class="form-group">
                            <label for="qnt">حالة المنتج ( {{ $data->name }} )</label>
                            <select wire:model="qnt" id="qnt" class="form-control  " style="width: 100%;">


                                <option value="5" class="text-gray">
                                    اختر الحالة الجديدة
                                </option>

                                <option value="1"> متوفر</option>
                                <option value="0"> غير متوفر</option>

                            </select>
                        </div>
                        <div class="col-sm-6 ">

                </div>
                    </div>
                </div>


                <div class="flex-row d-flex ">

                    <div class="col-sm-6">

                        <div class="form-group">
                            <label for="qnt">حالة المنتج ( {{ $data->name }} )</label>
                            <select wire:model="qnt" id="qnt" class="form-control  " style="width: 100%;">


                                <option class="text-gray">
                                    اختر الحالة الجديدة
                                </option>

                                <option value="1"> متوفر</option>
                                <option value="0"> غير متوفر</option>

                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" wire:model="hasRecipe" id="hasRecipe" class="form-check-input">
                            <label for="hasRecipe" class="form-check-label">يحتوي على وصفة</label>
                        </div><br>
                    </div>
                </div>



                @if (session('done'))
                    <div class="callout bg-success flex-row align-items-center callout-success">
                        <h5><i class="fa text-xl pl-1 fa-check-circle" aria-hidden="true"></i>تم تعديل المنتج بنجاح
                            بنجاح
                        </h5>
                    </div>
                @endif
                <!-- /.card-body -->

                <div class="card-footer">
                    <button wire:submit="update" class="btn btn-primary ">
                        تحديث
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
