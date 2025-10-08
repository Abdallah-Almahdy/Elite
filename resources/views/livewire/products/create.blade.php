<div class="card card-primary">
    <div class="card-header">
        <h5 class=" text-center">إضافة صنف جديد</h5>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form wire:submit="create" role="form">
        <div class="card-body">

            <div class="flex-row">

                <div class="col-sm-6 ">

                    <div class="form-group">
                        <label for="name">الاسم</label>
                        <input wire:model="name" type="text" class="form-control" id="name"
                            placeholder="ادخل الاسم">
                    </div>
                </div>
                @error('name')
                    <div class="text-danger"> {{ $message }}</div>
                @enderror



                <div class="col-sm-6 ">
                    <div class="form-group">
                        <label for="price">السعر</label>
                        <input wire:model="price" type="text" class="form-control" id="price"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" ">
                    </div>
                </div>
            </div>

            @error('price')
    <div class="text-danger"> {{ $message }}</div>
@enderror
        <div class="flex-row">

            <div class="col-sm-6 ">

                <div class="form-group">
                    <label for="bar_code">بار كود</label>
                    <input wire:model="bar_code" type="text" class="form-control" id="bar_code"
                        placeholder="ادخل البار كود">
                </div>
            </div>
            <div class="col-sm-6 ">

                <div class="form-group">
                    <label for="description"> الوصف</label>
                    <input wire:model="description" type="text" class="form-control" id="description"
                        placeholder="ادخل  الوصف">
                </div>
            </div>
        </div>



            <div class="flex-row row">


                <div class="col-sm-6">

                    <div class="form-group">
                        <label for="section">القسم</label>
                        <select wire:model="section" id="section" class="form-control  " style="width: 100%;">

                            <option class="text-gray">اختر القسم...</option>      @foreach ($sections as $section)
                        <option value="{{ $section->id }}"> {{ $section->name }}</option>
                        @endforeach

                        </select>
                    </div>
                </div>
                @error('section_id')
                    <div class="text-danger"> الرجاء اختيار القسم</div>
                @enderror

                @can('showQntOption')
                    <div class="row d-flex  justify-content-center align-items-center">

                        <div class="col-sm-6  d-flex justify-content-center align-items-center">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="stock">
                                <label class="custom-control-label" for="stock">المخزون</label>

                            </div>
                        </div>


                        <div id="stockQntDiv" class="col-sm-6  invisible ">
                            <div class="form-group">
                                <label for="stockQnt">الكمية</label>
                                <input wire:model="stockQnt" name="stockQnt" type="number" class="form-control"
                                    id="stockQnt" placeholder=" الكمية بالمخزن">
                            </div>
                        </div>

                    </div>
                @endcan

                <div class="col-sm-6 ">
                    <div class="form-check">
                        <input type="checkbox" wire:model="hasRecipe" id="hasRecipe" class="form-check-input">
                        <label for="hasRecipe" class="form-check-label">يحتوي على وصفة</label>
                    </div>
                </div>


            </div>




        </div>
        <div class="flex-row d-flex ">











            <div class="col-sm-6 flex-row">
                <div class="mb-3">
                    <label for="photo" class="form-label">الصوره</label>
                    <input wire:model="photo" class="form-control" type="file" id="photo">
                </div>

                @if ($photo)
                    <img class="border w-25" src="{{ $photo->temporaryUrl() }}">
                @endif
            </div>
            @error('photo')
                <div class="text-danger"> error</div>
            @enderror
        </div>

        @if (session('done'))
            <div class="callout bg-success flex-row align-items-center callout-success">
                <h5><i class="fa text-xl pl-1 fa-check-circle" aria-hidden="true"></i>تم اضافة منتج جديد
                    بنجاح
                </h5>
            </div>
        @endif
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" id="done" class="btn btn-primary">اضافة</button>
        </div>
    </form>
</div>
