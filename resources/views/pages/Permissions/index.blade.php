@extends('admin.app')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">إدارة صلاحيات المستخدمين</h3>

    {{-- ✅ رسالة نجاح --}}
    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- 🔹 اختيار المستخدم --}}
    <form action="{{ route('Permissions.index') }}" method="GET" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <label for="user" class="form-label">اختر المستخدم</label>
                <select name="user_id" id="user" class="form-select" onchange="this.form.submit()">
                    <option value="">ــــــ</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- ✅ عرض الصلاحيات --}}
    @if ($selectedUser)
        <form action="{{ route('Permissions.update', $selectedUser->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5>صلاحيات المستخدم: {{ $selectedUser->name }}</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>الصلاحية</th>
                                <th>تفعيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $perm)
                                <tr>
                                    <td>{{ __('lan.' . $perm->name) }}</td>

                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="{{ $perm->name }}"
                                                   {{ $selectedUser->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success px-4">💾 حفظ التغييرات</button>
                </div>
            </div>
        </form>
    @else
        <p class="text-muted text-center mt-5">الرجاء اختيار مستخدم لعرض صلاحياته</p>
    @endif
</div>
@endsection
