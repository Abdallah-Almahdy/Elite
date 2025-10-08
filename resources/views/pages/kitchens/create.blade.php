@extends('admin.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h5 class="text-center">إضافةمطبخ جديد</h5>
        </div>
        <form method="POST" action="{{ route('kitchens.store') }}" role="form">
            @csrf
            <div class="card-body">
                @if (session('success'))
                    <div class="callout callout-success">
                        <h5><i class="icon fa fa-check"></i> {{ session('success') }}</h5>
                    </div>
                @endif



                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">حالة التفعيل </label>
                            <select class="form-control" id="active" name="active">
                                <option value="9"> </option>
                                <option value="1">مفعل</option>
                                <option value="0">غير مفعل
                                </option>
                            </select>
                            @error('active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
        </form>
    </div>
@endsection
